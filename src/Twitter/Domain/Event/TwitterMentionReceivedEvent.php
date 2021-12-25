<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Twitter\Domain\Event;

use NachoBrito\TTBot\Common\Domain\Bus\Event\Event;
use NachoBrito\TTBot\Twitter\Domain\Model\Tweet;

/**
 * 
 *
 * @author nacho
 */
class TwitterMentionReceivedEvent implements Event {
    /**
     * 
     * @var Tweet
     */
    private $tweet;
    
    public function __construct(Tweet $tweet) {
        $this->tweet = $tweet;
    }

    public function getTweet(): Tweet {
        return $this->tweet;
    }

}
