<?php

declare( strict_types=1 );


namespace NachoBrito\TTBot\Article\Application;

use NachoBrito\TTBot\Article\Domain\Event\ArticleSummaryCreatedEvent;
use NachoBrito\TTBot\Common\Domain\Bus\Event\EventSubscriber;
use NachoBrito\TTBot\Common\Domain\Logger;

/**
 * 
 *
 * @author nacho
 */
class LogSummaryOnSummaryCreated implements EventSubscriber{
    
    /**
     * 
     * @var Logger
     */
    private $loggger;
    
    /**
     * 
     * @param Logger $loggger
     */
    public function __construct(Logger $loggger) {
        $this->loggger = $loggger;
    }

    /**
     * 
     * @param ArticleSummaryCreatedEvent $evt
     */
    public function __invoke(ArticleSummaryCreatedEvent $evt) {
        $msg = "New summary created: \n";
        $msg .= "\tSource: {$evt->getSummary()->getArticle()->getUrl()} \n";
        //$msg .= "Text: {$evt->getSummary()->getArticle()->getText()} \n";
        $msg .= "\tLanguage: {$evt->getSummary()->getArticle()->getLanguage()}\n";
        $msg .= "\tSentences:\n";
        foreach($evt->getSummary()->getSentences() as $key => $sentence)
        {
            $msg .= "\t$key => $sentence\n";
        }
        $this->loggger->info($msg);
    }

}
