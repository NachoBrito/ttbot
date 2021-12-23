<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Twitter\Infraestructure;

use Coderjerk\BirdElephant\BirdElephant;
use DateTime;
use DateTimeZone;
use NachoBrito\TTBot\Common\Domain\LoggerInterface;
use NachoBrito\TTBot\Twitter\Domain\Tweet;
use NachoBrito\TTBot\Twitter\Domain\TwitterClient;

/**
 * 
 *
 * @author nacho
 */
class BirdElephantTwitterClient implements TwitterClient {

    /**
     * 
     * @var BirdElephant
     */
    private $client;
    
    /**
     * 
     * @var LoggerInterface
     */
    private $logger;

    /**
     * 
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }

    /**
     * 
     * @return BirdElephant
     */
    private function getClient() {
        if (!$this->client) {
            $credentials = array(
                'bearer_token' => getenv('TWITTER_BEARER_TOKEN'), 
                'consumer_key' => getenv('TWITTER_API_KEY'),
                'consumer_secret' => getenv('TWITTER_API_SECRET'),
            );

            $this->client = new BirdElephant($credentials);
        }
        return $this->client;
    }

    /**
     * 
     */
    public function getNewMentions() {
        $client = $this->getClient();
        
        $user = $client->user(getenv('TWITTER_USERNAME'));
        
        $mentions = $user->mentions();
        
        $data = $mentions->data;
        $meta = $mentions->meta;
        
        //todo: save this
        $newest_id = $meta->newest_id;
        
        $result = [];
        foreach($data as $mention)
        {
            $info = $client->tweets()->get($mention->id, [
                'expansions' => 'author_id',
                'tweet.fields' => 'author_id,created_at,lang,public_metrics',
                'user.fields' => 'name'
            ]);
            
            
            $user = $info->includes->users[0];
            $created_at = DateTime::createFromFormat(DateTime::RFC3339_EXTENDED,$info->data->created_at);            
            $tweet = 
                    (new Tweet())
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
