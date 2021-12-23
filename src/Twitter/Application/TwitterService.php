<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Twitter\Application;

use DateTime;
use NachoBrito\TTBot\Common\Domain\LoggerInterface;
use NachoBrito\TTBot\Common\Domain\Storage;
use NachoBrito\TTBot\Twitter\Domain\Model\Tweet;
use NachoBrito\TTBot\Twitter\Domain\TwitterClient;


/**
 * 
 *
 * @author nacho
 */
class TwitterService {

    
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
        $this->logger->debug("Loading mentions...");
        $mentions = $this->client->getMentions();

        $data = $mentions->data;
        $meta = $mentions->meta;

        //todo: save this
        $newest_id = $meta->newest_id;

        $result = [];
        foreach ($data as $mention) {
            $info = $this->client->getTweet($mention->id, [
                'expansions' => 'author_id',
                'tweet.fields' => 'author_id,created_at,lang,public_metrics',
                'user.fields' => 'name'
            ]);


            $user = $info->includes->users[0];
            $created_at = DateTime::createFromFormat(DateTime::RFC3339_EXTENDED, $info->data->created_at);
            $tweet = (new Tweet())
                    ->setAuthorName($user->name)
                    ->setAuthorUsername($user->username)
                    ->setAuthorId($user->id)
                    ->setCreatedAt($created_at)
                    ->setId($info->data->id)
                    ->setLang($info->data->lang)
                    ->setText($info->data->text)
            ;
            $result[] = $tweet;
        }
//        $json = json_encode($data);
//        $array = json_decode($json, TRUE);
//        return $array;

        return $result;
    }

}
