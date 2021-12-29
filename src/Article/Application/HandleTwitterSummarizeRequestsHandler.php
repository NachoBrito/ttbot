<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Article\Application;

use NachoBrito\TTBot\Article\Domain\ArticleLoader;
use NachoBrito\TTBot\Article\Domain\ArticleSummarizer;
use NachoBrito\TTBot\Article\Domain\URLExtractor;
use NachoBrito\TTBot\Common\Domain\Bus\Command\CommandHandler;
use NachoBrito\TTBot\Common\Domain\Logger;
use NachoBrito\TTBot\Twitter\Application\TwitterService;
use NachoBrito\TTBot\Twitter\Domain\Model\Tweet;
use NachoBrito\TTBot\Twitter\Domain\Model\TweetReference;

/**
 * 
 *
 * @author nacho
 */
class HandleTwitterSummarizeRequestsHandler implements CommandHandler {

    /**
     * 
     * @var TwitterService
     */
    private $twitter;

    /**
     * 
     * @var Logger
     */
    private $logger;

    /**
     * 
     * @var ArticleSummarizer
     */
    private $summarizer;

    /**
     * 
     * @var ArticleLoader
     */
    private $loader;

    /**
     * 
     * @var URLExtractor
     */
    private $urlExtractor;

    public function __construct(TwitterService $twitter, Logger $logger, ArticleSummarizer $summarizer, ArticleLoader $loader, URLExtractor $urlExtractor) {
        $this->twitter = $twitter;
        $this->logger = $logger;
        $this->summarizer = $summarizer;
        $this->loader = $loader;
        $this->urlExtractor = $urlExtractor;
    }

    /**
     * 
     * @param HandleTwitterSummarizeRequestsCommand $cmd
     * @return type
     */
    public function __invoke(HandleTwitterSummarizeRequestsCommand $cmd) {

        $mentions = $this->twitter->getNewMentions();
        $num = count($mentions);
        if (!$num) {
            $this->logger->info("No new mentions found to process.");
            return;
        }

        $max_tweets = (int) getenv('THREADS_MAX_TWEETS');
        foreach ($mentions as $mention) {
            /** @var Tweet $mention */
            $this->logger->info("Processing mention " . $mention->getId());

            //VALIDATE
            
            
            $url = $this->findUrlToSummarize($mention);
            if (!$url) {
                $this->logger->info("Didn't find any url to summarize.");
                return;
            }

            $this->logger->info("Found url: $url. Sumarizing");
            $article = $this->loader->loadArticle($url);

            $summary = $this->summarizer->summarize($article);
            $this->logger->info("Summary generated with " . count($summary->getSentences()) . " sentences");

            $sentences = [];
            foreach($summary->getSentences() as $s)
            {
                $sentences[] = "\"$s\" $url";
            }
            
            $this
                    ->twitter
                    ->postReplyThread($mention, $sentences, $max_tweets);
        }
    }

    /**
     * 
     * @param Tweet $tweet
     * @return string the first url found in tweet or any referenced 
     *         tweet included.
     */
    private function findUrlToSummarize(Tweet $tweet): ?string {
        //find url in tweet
        $urls = $this->urlExtractor->getUrls($tweet->getText());
        if ($urls) {
            return $urls[0];
        }

        //if not found, find in mention
        $refs = $tweet->getReferences();
        foreach ($refs as $referencedTweet) {
            /** @var TweetReference $referencedTweet */
            $urls = $this
                    ->urlExtractor
                    ->getUrls($referencedTweet->getTweet()->getText());
            if ($urls) {
                return $urls[0];
            }
        }
        return NULL;
    }

}
