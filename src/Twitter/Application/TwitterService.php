<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Twitter\Application;

use DateTime;
use NachoBrito\TTBot\Common\Domain\LoggerInterface;
use NachoBrito\TTBot\Common\Domain\Storage;
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
     * @var LoggerInterface
     */
    private $logger;

    /**
     * 
     * @param TwitterClient $client
     * @param Storage $storage
     * @param LoggerInterface $logger
     */
    public function __construct(TwitterClient $client, Storage $storage, LoggerInterface $logger) {
        $this->client = $client;
        $this->storage = $storage;
        $this->logger = $logger;
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
            if($mention->id <= $last_id)
            {
                $this->logger->debug("Ignoring old tweet {$mention->id}");
                continue;
            }
            
            $info = $this->client->getTweet($mention->id, [
                'expansions' => 'author_id,referenced_tweets.id,referenced_tweets.id.author_id',
                'tweet.fields' => 'author_id,created_at,lang,public_metrics,referenced_tweets',
                'user.fields' => 'name'
            ]);
            
            $result[] = TweetFactory::fromAPIResponse($info);
        }
        
        $newest_id = $meta->newest_id;
        $this->logger->debug("Newest mention id: $newest_id");
        $this->storage->set(self::LAST_MENTION_ID, $newest_id);

        return $result;
    }

}
