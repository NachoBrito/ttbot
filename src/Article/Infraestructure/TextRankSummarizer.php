<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Article\Infraestructure;

use NachoBrito\TTBot\Article\Domain\ArticleSummarizer;
use NachoBrito\TTBot\Article\Domain\Error\LanguageNotSupportedException;
use NachoBrito\TTBot\Article\Domain\Model\Article;
use NachoBrito\TTBot\Article\Domain\Model\ArticleSummary;
use NachoBrito\TTBot\Article\Domain\Model\Language;
use PhpScience\TextRank\Tool\Graph;
use PhpScience\TextRank\Tool\Parser;
use PhpScience\TextRank\Tool\Score;
use PhpScience\TextRank\Tool\StopWords\English;
use PhpScience\TextRank\Tool\StopWords\French;
use PhpScience\TextRank\Tool\StopWords\German;
use PhpScience\TextRank\Tool\StopWords\Italian;
use PhpScience\TextRank\Tool\StopWords\Norwegian;
use PhpScience\TextRank\Tool\StopWords\Russian;
use PhpScience\TextRank\Tool\StopWords\Spanish;
use PhpScience\TextRank\Tool\StopWords\StopWordsAbstract;
use PhpScience\TextRank\Tool\Summarize;

/**
 * 
 * @codeCoverageIgnore
 * @author nacho
 */
class TextRankSummarizer implements ArticleSummarizer {

    //Ignore short words.
    const MINIMUM_WORD_LENGTH = 3;
    
    /**
     * 
     * @param Article $article
     * @return ArticleSummary
     */
    public function summarize(Article $article): ArticleSummary {
        $stopWords = $this->getStopWords($article);        
        $result = $this->getSummary($article->getText(), $stopWords);
        return new ArticleSummary($article, $result);
    }

    /**
     * 
     * @param Article $article
     * @return StopWordsAbstract
     */
    private function getStopWords(Article $article): StopWordsAbstract {
        switch ($article->getLanguage()) {
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

    /**
     * 
     * @param type $rawText
     * @return array<string>
     */
    private function getSummary(string $rawText, StopWordsAbstract $stopWords): array {
        $parser = new Parser();
        $parser->setMinimumWordLength(self::MINIMUM_WORD_LENGTH);        
        $parser->setRawText($rawText);

        $parser->setStopWords($stopWords);

        $text = $parser->parse();
        
        /*
         * IMPORTANT: 
         * summarize->getSummarize will return the most relevant sentences 
         * in text, in order or appearance, *not by relevance*. Using a value 
         * too big here will cause non-relevant sentences that appear at the 
         * beginning of the document to be included in the summary.
         */
        $maximumSentences = (int) getenv('THREADS_MAX_TWEETS');

        $graph = new Graph();
        $graph->createGraph($text);

        $score = new Score();
        $scores = $score->calculate($graph, $text);

        $summarize = new Summarize();

        $keywordLimit = 3;
        
        /*
         * Summarize text.
         *
         * It retrieves the summarized text in array.
         *
         * @param array $scores        Keywords with scores. Score is the key.
         * @param Graph $graph         The graph of the text.
         * @param Text  $text          Text object what stores all text data.
         * @param int   $keyWordLimit  How many keyword should be used to find the
         *                             important sentences.
         * @param int   $sentenceLimit How many sentence should be retrieved.
         * @param int   $type          The type of summarizing. Possible values are
         *                             the constants of this class.
         *
         * @return array An array from sentences.
         */
        $sentences = $summarize->getSummarize(
                        $scores,
                        $graph,
                        $text,
                        $keywordLimit,
                        $maximumSentences,
                        Summarize::GET_ALL_IMPORTANT
        );
        
        
        return array_unique($sentences);
    }

}
