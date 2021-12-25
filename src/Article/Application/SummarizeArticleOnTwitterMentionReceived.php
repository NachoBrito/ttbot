<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Article\Application;

use NachoBrito\TTBot\Article\Domain\URLExtractor;
use NachoBrito\TTBot\Common\Domain\Bus\Command\CommandBus;
use NachoBrito\TTBot\Common\Domain\Bus\Event\EventSubscriber;
use NachoBrito\TTBot\Common\Domain\Logger;
use NachoBrito\TTBot\Twitter\Domain\Event\TwitterMentionReceivedEvent;
use NachoBrito\TTBot\Twitter\Domain\Model\Tweet;

/**
 * 
 *
 * @author nacho
 */
class SummarizeArticleOnTwitterMentionReceived implements EventSubscriber {

    /**
     * 
     * @var URLExtractor
     */
    private $urlExtractor;

    /**
     * 
     * @var Logger
     */
    private $logger;

    /**
     * 
     * @var CommandBus
     */
    private $commandBus;

    /**
     * 
     * @param URLExtractor $urlExtractor
     * @param Logger $logger
     * @param CommandBus $commandBus
     */
    public function __construct(URLExtractor $urlExtractor, Logger $logger, CommandBus $commandBus) {
        $this->urlExtractor = $urlExtractor;
        $this->logger = $logger;
        $this->commandBus = $commandBus;
    }

    /**
     * 
     * @param TwitterMentionReceived $evt
     */
    public function __invoke(TwitterMentionReceivedEvent $evt) {
        $mention = $evt->getTweet();

        $url = $this->findUrlToSummarize($mention);
        if (!$url) {
            $this->logger->info("Didn't find any url to summarize.");
            return;
        }
        
        $this->logger->info("Found url: $url Executing summarize command.");
        $cmd = new SummarizeUrlCommand($url);
        $this->commandBus->dispatch($cmd);
    }

    /**
     * 
     * @param Tweet $tweet
     * @return string the first url found in tweet or any referenced 
     *         tweet included.
     */
    private function findUrlToSummarize(Tweet $tweet): string {
        //find url in tweet
        $urls = $this->urlExtractor->getUrls($tweet->getText());
        if ($urls) {
            return $urls[0];
        }
        
        //if not found, find in mention
        $refs = $tweet->getReferences();
        foreach ($refs as $referencedTweet) {
            /** @var Tweet $referencedTweet */
            $urls = $this->urlExtractor->getUrls($referencedTweet->getText());
            if($urls)
            {
                return $urls[0];
            }
        }
        return NULL;
    }

}
