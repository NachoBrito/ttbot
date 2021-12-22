<?php

declare( strict_types=1 );


namespace NachoBrito\TTBot\Article\Application;

use NachoBrito\TTBot\Article\Domain\Event\ArticleSummaryCreatedEvent;
use NachoBrito\TTBot\Common\Domain\Bus\Event\EventSubscriber;
use NachoBrito\TTBot\Common\Domain\LoggerInterface;

/**
 * 
 *
 * @author nacho
 */
class LogSummaryOnSummaryCreated implements EventSubscriber{
    
    /**
     * 
     * @var LoggerInterface
     */
    private $loggger;
    
    /**
     * 
     * @param LoggerInterface $loggger
     */
    public function __construct(LoggerInterface $loggger) {
        $this->loggger = $loggger;
    }

    /**
     * 
     * @param ArticleSummaryCreatedEvent $evt
     */
    public function __invoke(ArticleSummaryCreatedEvent $evt) {
        $msg = "New summary created: \n";
        $msg .= "Source: {$evt->getSummary()->getArticle()->getUrl()} \n";
        $msg .= "Text: {$evt->getSummary()->getArticle()->getText()} \n";
        $msg .= "Language: {$evt->getSummary()->getArticle()->getLanguage()}\n";
        $msg .= "Sentences:\n";
        $msg .= json_encode($evt->getSummary()->getSentences(), JSON_PRETTY_PRINT);
        
        $this->loggger->info($msg);
    }

}
