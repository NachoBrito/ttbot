<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Twitter\Domain\Model;

/**
 * 
 *
 * @author nacho
 */
class TweetReference {    
    /**
     * 
     * @var string
     */
    private $type;
    
    /**
     * 
     * @var Tweet
     */
    private $tweet;
    
    /**
     * 
     * @return string
     */
    public function getType(): string {
        return $this->type;
    }

    /**
     * 
     * @return Tweet
     */
    public function getTweet(): Tweet {
        return $this->tweet;
    }

    /**
     * 
     * @param string $type
     * @return $this
     */
    public function setType(string $type) {
        $this->type = $type;
        return $this;
    }

    /**
     * 
     * @param Tweet $tweet
     * @return $this
     */
    public function setTweet(Tweet $tweet) {
        $this->tweet = $tweet;
        return $this;
    }


}
