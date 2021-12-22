<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Article\Infraestructure;

use NachoBrito\TTBot\Article\Domain\ArticleSummarizer;
use NachoBrito\TTBot\Article\Domain\Error\LanguageNotSupportedException;
use NachoBrito\TTBot\Article\Domain\Model\Article;
use NachoBrito\TTBot\Article\Domain\Model\ArticleSummary;
use NachoBrito\TTBot\Article\Domain\Model\Language;
use PhpScience\TextRank\TextRankFacade;
use PhpScience\TextRank\Tool\StopWords\English;
use PhpScience\TextRank\Tool\StopWords\French;
use PhpScience\TextRank\Tool\StopWords\German;
use PhpScience\TextRank\Tool\StopWords\Indonesian;
use PhpScience\TextRank\Tool\StopWords\Italian;
use PhpScience\TextRank\Tool\StopWords\Norwegian;
use PhpScience\TextRank\Tool\StopWords\Russian;
use PhpScience\TextRank\Tool\StopWords\Spanish;
use PhpScience\TextRank\Tool\StopWords\StopWordsAbstract;

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
//        $result = $api->getHighlights($article->getText());
        $result = $api->summarizeTextCompound($article->getText());
        // Array of the most important sentences from the text:
        //$result = $api->summarizeTextBasic($article->getText());

        return new ArticleSummary($article, $result);
    }

    /**
     * 
     * @param Article $article
     * @return StopWordsAbstract
     */
    private function getStopWords(Article $article): StopWordsAbstract {
        switch($article->getLanguage())
        {
            case Language::ENGLISH:
                return new English();
            case Language::FRENCH:
                return new French();
            case Language::GERMAN:
                return new German();
            case Language::ITALIAN:
                return new Italian();
            case Language::NORWEGIAN:
                return new Norwegian();
            case Language::RUSSIAN:
                return new Russian();
            case Language::SPANISH:
                return new Spanish();
            default:
                throw new LanguageNotSupportedException("Language {$article->getLanguage()} is not supported.");
        }        
    }

}
