<?php

namespace NachoBrito\TTBot\Article\Infraestructure;

use NachoBrito\TTBot\Article\Domain\Model\Article;
use PHPUnit\Framework\TestCase;

/**
 * Description of TextRankSummarizerTest
 *
 * @author nacho
 */
class TextRankSummarizerTest extends TestCase {
    public function testEdgeCase1()
    {
        $case = json_decode(file_get_contents(__DIR__ . '/edge_case_1.json'), TRUE);
        $article = (new Article())
                ->setLanguage($case['language'])
                ->setMetadata($case['metadata'])
                ->setText($case['text'])
//                ->setTitle($case['title'])
                ->setUrl($case['url']);
        
        $summarizer = new TextRankSummarizer();
        
        $summary = $summarizer->summarize($article);        
        self::assertCount(5, $summary->getSentences());
    }
}
