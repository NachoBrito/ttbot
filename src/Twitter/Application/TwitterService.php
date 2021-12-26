<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Twitter\Application;

use NachoBrito\TTBot\Common\Domain\Bus\Event\EventBus;
use NachoBrito\TTBot\Common\Domain\Logger;
use NachoBrito\TTBot\Common\Domain\Storage;
use NachoBrito\TTBot\Twitter\Domain\Event\TwitterMentionReceivedEvent;
use NachoBrito\TTBot\Twitter\Domain\TweetFactory;
use NachoBrito\TTBot\Twitter\Domain\TwitterClient;

/**
 * 
 *
 * @author nacho
 */
class TwitterService {

    const LAST_MENTION_ID = 'LAST_MENTION_ID';

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

}
