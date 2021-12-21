<?php

declare( strict_types=1 );


namespace NachoBrito\TTBot\Article\Application;

use NachoBrito\TTBot\Article\Domain\ArticleLoader;
use NachoBrito\TTBot\Article\Domain\ArticleSummarizer;
use NachoBrito\TTBot\Article\Domain\Event\ArticleSummaryCreatedEvent;
use NachoBrito\TTBot\Common\Domain\Bus\Command\CommandHandler;
use NachoBrito\TTBot\Common\Domain\Bus\Event\EventBus;

/**
 * 
 *
 * @author nacho
 */
class SummarizeUrlHandler implements CommandHandler{
    /**
     * 
     * @var ArticleLoader
     */
    private $loader;

    
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


    public function __construct(ArticleLoader $loader, ArticleSummarizer $summarizer, EventBus $eventBus) {
        $this->loader = $loader;
        $this->summarizer = $summarizer;
        $this->eventBus = $eventBus;
    }

        
    /**
     * 
     * @param SummarizeUrlCommand $command
     */
    public function __invoke(SummarizeUrlCommand $command) {
        $url = $command->getUrl();
        $article = $this->loader->loadArticle($url);
        
        $summary = $this->summarizer->summarize($article);        
        $event = new ArticleSummaryCreatedEvent($summary);
        
        $this->eventBus->dispatch($event);        
    }

}
