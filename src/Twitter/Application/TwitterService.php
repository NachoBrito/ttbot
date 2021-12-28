<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Twitter\Application;

use NachoBrito\TTBot\Common\Domain\Bus\Event\EventBus;
use NachoBrito\TTBot\Common\Domain\Logger;
use NachoBrito\TTBot\Common\Domain\Storage;
use NachoBrito\TTBot\Twitter\Domain\Event\TwitterMentionReceivedEvent;
use NachoBrito\TTBot\Twitter\Domain\Model\Tweet;
use NachoBrito\TTBot\Twitter\Domain\TweetFactory;
use NachoBrito\TTBot\Twitter\Domain\TwitterClient;

/**
 * 
 *
 * @author nacho
 */
class TwitterService {

    const LAST_MENTION_ID = 'LAST_MENTION_ID';
    const DEFAULT_MAX_THREAD_LENGTH = 5;

    private $forbidden_search = [
    ];
    private $forbidden_replace = [
    ];

    /**
     * 
     * @var TwitterClient
     */
    private $client;

    /**
     * 
     * @var Storage
     */
    private $storage;

    /**
     * 
     * @var Logger
     */
    private $logger;

    /**
     * 
     * @var EventBus
     */
    private $eventBus;

    /**
     * 
     * @param TwitterClient $client
     * @param Storage $storage
     * @param Logger $logger
     * @param EventBus $eventBus
     */
    public function __construct(TwitterClient $client, Storage $storage, Logger $logger, EventBus $eventBus) {
        $this->client = $client;
        $this->storage = $storage;
        $this->logger = $logger;
        $this->eventBus = $eventBus;

        $this->forbidden_search[] = '@' . getenv('TWITTER_USERNAME');
        $this->forbidden_replace[] = '{me}';
    }

    /**
     * 
     */
    public function getNewMentions() {
        $last_id = $this->storage->get(self::LAST_MENTION_ID);
        $this->logger->debug("Last loaded mention was $last_id");

        $this->logger->debug("Loading mentions...");
        $mentions = $this->client->getMentions();

        $data = $mentions->data;
        $meta = $mentions->meta;

        $result = [];
        foreach ($data as $mention) {
            if ($mention->id <= $last_id) {
                $this->logger->debug("Ignoring old tweet {$mention->id}");
                continue;
            }

            $info = $this->client->getTweet($mention->id, [
                'expansions' => 'author_id,referenced_tweets.id,referenced_tweets.id.author_id',
                'tweet.fields' => 'author_id,created_at,lang,public_metrics,referenced_tweets',
                'user.fields' => 'name'
            ]);

            $t = TweetFactory::fromAPIResponse($info);

            $this->eventBus->dispatch(new TwitterMentionReceivedEvent($t));

            $result[] = $t;
        }

        $newest_id = $meta->newest_id;
        $this->logger->debug("Newest mention id: $newest_id");
        $this->storage->set(self::LAST_MENTION_ID, $newest_id);

        return $result;
    }

    /**
     * Builds a thread with up to $max_tweets tweets, and posts it in reply
     * to $originalTweet.
     * 
     * @param Tweet $originalTweet
     * @param array $sentences
     * @param int $max_tweets
     */
    public function postReplyThread(Tweet $originalTweet, array $sentences, int $max_tweets = self::DEFAULT_MAX_THREAD_LENGTH) {
        $tweet_texts = $this->buildTweetTexts($sentences, $max_tweets);
        $reply_to_id = $originalTweet->getId();
        foreach ($tweet_texts as $text) {
            //Each tweet is a reply to the previous one.
            $reply_to_id = $this->client->tweet($text, $reply_to_id);
        }
    }

    /**
     * 
     * @param array $sentences
     * @param int $max_tweets
     */
    private function buildTweetTexts(array $sentences, int $max_tweets) {
        $tweets = [];
        $count = 0;
        $sufix = "\n%d/%d";
        $separator = "[...]";
        //assume max count will be 2 chars max.
        $max_length_env = (int) getenv('TRHEADS_MAX_TWEET_LENGTH');
        $max_length = $max_length_env ? $max_length_env : Tweet::DEFAULT_MAX_LENGTH;
        do {
            //FIFO
            $sentence = array_shift($sentences);
            $clean_sentence = $this->cleanForbiddenWords($sentence);
            $parts = $this->breakSentence($clean_sentence, $max_length, $separator, $sufix);

            if (count($parts) + $count > $max_tweets) {
                //do not include parts if there is no room for all of them.
                continue;
            }

            foreach ($parts as $part) {
                $tweets[] = trim($part);
                $count++;
                if ($count === $max_tweets) {
                    break;
                }
            }
        } while ($sentences && $count < $max_tweets);

        return $tweets;
    }

    /**
     * 
     * @param type $sentence
     * @param type $max_length
     */
    private function breakSentence($sentence, $max_length, $separator, $sufix) {
        if (strlen($sentence) <= $max_length) {
            return [$sentence];
        }

        $words = explode(' ', $sentence);
        $parts = [''];
        $i = 0;
        $sufix_length = strlen($sufix);
        $separator_length = strlen($separator);

        foreach ($words as $word) {
            $current_length = strlen($parts[$i]);
            $word_length = strlen($word);
            $total_length = $current_length + $word_length + $sufix_length + $separator_length + 1;
            if ($i > 0) {
                $total_length += ($separator_length + 1);
            }

            if ($total_length > $max_length) {
                $parts[$i] .= $separator;
                $i++;
                $parts[$i] = "$separator ";
            }
            $parts[$i] .= "$word ";
        }
        $num = count($parts);
        if ($num > 1) {
            foreach ($parts as $i => $part) {
                $part_number = $i + 1;
                $parts[$i] = trim($part) . sprintf($sufix, $part_number, $num);
            }
        }
        return $parts;
    }

    /**
     * 
     * @param type $candidate
     */
    private function cleanForbiddenWords(string $candidate): string {
        return str_replace($this->forbidden_search, $this->forbidden_replace, $candidate);
    }

}
