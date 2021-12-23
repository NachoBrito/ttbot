<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Twitter\Infraestructure;

use Coderjerk\BirdElephant\BirdElephant;
use NachoBrito\TTBot\Common\Domain\LoggerInterface;
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
     * @return mixed
     */
    public function getMentions() {
        $client = $this->getClient();
        $user = $client->user(getenv('TWITTER_USERNAME'));
        $mentions = $user->mentions();
        
        return $mentions;
    }


    /**
     * 
     * @param string $id
     * @param array $options
     * @return mixed
     */
    public function getTweet(string $id, array $options) {
        $client = $this->getClient();        
        $tweet = $client->tweets()->get($id, $options);        
        return $tweet;
    }

}
