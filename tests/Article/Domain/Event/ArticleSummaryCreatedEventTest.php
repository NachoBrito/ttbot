<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Article\Domain\Event;

use NachoBrito\TTBot\Article\Domain\Model\Article;
use NachoBrito\TTBot\Article\Domain\Model\ArticleSummary;
use PHPUnit\Framework\TestCase;

/**
 * 
 *
 * @author nacho
 */
class ArticleSummaryCreatedEventTest extends TestCase {

    public function testAll() {
        $a1 = new Article();
        $a2 = ["test1", "test2"];
        $o = new ArticleSummary($a1, $a2);
        
        $e = new ArticleSummaryCreatedEvent($o);
        
        self::assertSame($o, $e->getSummary());
    }

}
