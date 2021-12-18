<?php declare( strict_types=1 ); 
namespace NachoBrito\TTBot\Article\Application;

use NachoBrito\TTBot\Article\Application\SummarizeArticleCommand;
use NachoBrito\TTBot\Article\Domain\ArticleSummarizer;
use NachoBrito\TTBot\Article\Domain\Event\ArticleSummaryCreatedEvent;
use NachoBrito\TTBot\Common\Domain\Bus\Command\CommandHandler;
use NachoBrito\TTBot\Common\Domain\Bus\Event\EventBus;

class SummarizeArticleHandler implements CommandHandler
{
    /**     
     *
     * @var ArticleSummarizer
     */
    private $summarizer;

    /**
     * 
     *
     * @var EventBus
     */
    private $eventBus;


    /**
     * 
     * @param ArticleSummarizer $summarizer
     * @param EventBus $eventBus
     */
    function __construct(ArticleSummarizer $summarizer, EventBus $eventBus) {
        $this->summarizer = $summarizer;
        $this->eventBus = $eventBus;
    }

        
    /**
     * 
     * @param SummarizeArticleCommand $command
     * @return void
     */
    public function __invoke(SummarizeArticleCommand $command):void
    {
        $summary = $this->summarizer->summarize($command->getArticle());        
        $event = new ArticleSummaryCreatedEvent($summary);
        $this->eventBus->dispatch($event);
    }
}