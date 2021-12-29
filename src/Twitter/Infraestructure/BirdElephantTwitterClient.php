<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Twitter\Infraestructure;

use Coderjerk\BirdElephant\BirdElephant;
use NachoBrito\TTBot\Common\Domain\Logger;
use NachoBrito\TTBot\Twitter\Domain\Model\Tweet;
use NachoBrito\TTBot\Twitter\Domain\TwitterClient;

/**
 * 
 * @codeCoverageIgnore
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
     * @var Logger
     */
    private $logger;

    /**
     * 
     * @param Logger $logger
     */
    public function __construct(Logger $logger) {
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
                'token_identifier' => getenv('TWITTER_TOKEN_IDENTIFIER'),
                'token_secret' => getenv('TWITTER_TOKEN_SECRET'),
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
        $this->logger->info("Loading tweet $id");
        $client = $this->getClient();
        $tweet = $client->tweets()->get($id, $options);

//        $this->logger->debug("+++TWEET+++:\n" . json_encode($tweet, JSON_PRETTY_PRINT) . " \n\n");

        return $tweet;
    }


    /**
     * 
     * @param string $text
     * @param string $reply_to_id
     * @return string
     */
    public function tweet(string $text, string $reply_to_id = NULL): string {

        $tweet = (new \Coderjerk\BirdElephant\Compose\Tweet)
                ->text($text)
        ;

        if ($reply_to_id) {
            $reply = (new \Coderjerk\BirdElephant\Compose\Reply)
                    ->inReplyToTweetId($reply_to_id);
            $tweet->reply($reply);
        }

        $client = $this->getClient();
        $this->logger->debug(" >>> Tweet (reply to $reply_to_id): $text");
        $result = $client->tweets()->tweet($tweet);
        $this->logger->debug(" <<< " . json_encode($result));
        
        return $result->data->id;        
    }

}
