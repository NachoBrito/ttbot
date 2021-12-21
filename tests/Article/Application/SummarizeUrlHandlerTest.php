<?php
declare( strict_types=1 );

namespace NachoBrito\TTBot\Article\Apoplication;

use NachoBrito\TTBot\Article\Application\SummarizeUrlCommand;
use NachoBrito\TTBot\Article\Application\SummarizeUrlHandler;
use NachoBrito\TTBot\Article\Domain\Article;
use NachoBrito\TTBot\Article\Domain\ArticleLoader;
use NachoBrito\TTBot\Article\Domain\ArticleSummarizer;
use NachoBrito\TTBot\Article\Domain\Event\ArticleSummaryCreatedEvent;
use NachoBrito\TTBot\Common\Domain\Bus\Event\Event;
use NachoBrito\TTBot\Common\Domain\Bus\Event\EventBus;
use PHPUnit\Framework\TestCase;



/**
 * 
 *
 * @author nacho
 */
class SummarizeUrlHandlerTest extends TestCase {

    public function testInvoke():void
    {
        $url = "TheURL";
        
        $command = new SummarizeUrlCommand($url);
        
        $summarizer = $this
                ->getMockBuilder(ArticleSummarizer::class)
                ->getMock();
        $summarizer
                ->expects($this->once())
                ->method('summarize')
                ;
        
        $article = (new Article())
                ->setMetadata(["meta" => "data"])
                ->setText("Text")
                ->setTitle("Title")
                ->setUrl($url); 
        
        $loader = $this
                ->getMockBuilder(ArticleLoader::class)
                ->getMock();
        $loader
                ->expects($this->once())
                ->method('loadArticle')
                ->with($url)
                ->willReturn($article)
                ;
        
        
        $eventBus = new class() implements EventBus
        {            
            /**
             * 
             * @var array<Event>
             */
            private $events = [];

            /**
             * 
             * @param Event $event
             * @return void
             */
            public function dispatch(Event $event): void {
                $this->events[] = $event;
            }
            /**
             * 
             * @return array<Event>
             */
            public function getEvents():array
            {
                return $this->events;
            }
        } ;
        
        $handler = new SummarizeUrlHandler($loader, $summarizer, $eventBus);
        
        $handler($command);
        
        $events = $eventBus->getEvents();
        self::assertSame(1, count($events), "Event not fired");        
        self::assertInstanceOf(ArticleSummaryCreatedEvent::class, $events[0]);
    }
}
