<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Twitter\Domain\Model;

use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * 
 *
 * @author nacho
 */
class TweetTest extends TestCase {

    public function testGetters() {
        $now = new DateTime();
        $tweet = (new Tweet())
                ->setAuthorName("authorName")
                ->setAuthorUsername("authorUsername")
                ->setAuthorId("authorid")
                ->setCreatedAt($now)
                ->setId("id")
                ->setLang("lang")
                ->setText("text")
        ;
        
        self::assertSame("authorid", $tweet->getAuthorId());
        self::assertSame("authorName", $tweet->getAuthorName());
        self::assertSame("authorUsername", $tweet->getAuthorUsername());
        self::assertSame($now, $tweet->getCreatedAt());
        self::assertSame("id", $tweet->getId());
        self::assertSame("lang", $tweet->getLang());
        self::assertSame("text", $tweet->getText());
    }

}
