<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Article\Apoplication;

use NachoBrito\TTBot\Article\Application\LogSummaryOnSummaryCreated;
use NachoBrito\TTBot\Article\Domain\Event\ArticleSummaryCreatedEvent;
use NachoBrito\TTBot\Article\Domain\Model\Article;
use NachoBrito\TTBot\Article\Domain\Model\ArticleSummary;
use NachoBrito\TTBot\Common\Domain\Logger;
use PHPUnit\Framework\TestCase;

/**
 * 
 *
 * @author nacho
 */
class LogSummaryOnSummaryCreatedTest extends TestCase {

    /**
     * 
     */
    public function testInvoke() {
        $logger = $this
                ->getMockBuilder(Logger::class)
                ->getMock();
        $logger
                ->expects($this->once())
                ->method('info')
        ;

        $subscriber = new LogSummaryOnSummaryCreated($logger);

        $article = (new Article())
                ->setMetadata(["meta" => "data"])
                ->setText("Text")
                ->setTitle("Title")
                ->setLanguage('es')
                ->setUrl("theUrl");
        $sentences = ['one', 'two'];
        $summary = new ArticleSummary($article, $sentences);
        
        $evt = new ArticleSummaryCreatedEvent($summary);
        
        $subscriber($evt);
        
    }

}
