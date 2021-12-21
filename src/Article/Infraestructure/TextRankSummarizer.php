<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Article\Infraestructure;

use NachoBrito\TTBot\Article\Domain\Article;
use NachoBrito\TTBot\Article\Domain\ArticleSummarizer;
use NachoBrito\TTBot\Article\Domain\ArticleSummary;
use PhpScience\TextRank\TextRankFacade;
use PhpScience\TextRank\Tool\StopWords\English;

/**
 * 
 *
 * @author administrador
 */
class TextRankSummarizer implements ArticleSummarizer {

    public function summarize(Article $article): ArticleSummary {

        $api = new TextRankFacade();
        
        $stopWords = $this->getStopWords($article);
        $api->setStopWords($stopWords);

        // Array of the most important keywords:
        //$result = $api->getOnlyKeyWords($text);
        // Array of the sentences from the most important part of the text:
        //$result = $api->getHighlights($text);
        // Array of the most important sentences from the text:
        $result = $api->summarizeTextBasic($article->getText());

        return new ArticleSummary($article, $result);
    }

    /**
     * 
     * @param Article $article
     * @return StopWordsAbstract
     */
    private function getStopWords(Article $article): StopWordsAbstract {
        return new English();
    }

}
