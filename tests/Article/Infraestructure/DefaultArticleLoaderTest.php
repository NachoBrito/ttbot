<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Article\Infraestructure;

use NachoBrito\TTBot\Common\Domain\HTMLTextExtractor;
use NachoBrito\TTBot\Common\Domain\HTTPClient;
use NachoBrito\TTBot\Common\Domain\LanguageDetector;
use NachoBrito\TTBot\Common\Domain\UserAgentsHelper;
use PHPUnit\Framework\TestCase;

/**
 * 
 *
 * @author nacho
 */
class DefaultArticleLoaderTest extends TestCase {

    public function testLoad() {
        $url = "The URL";
        $html = "The HTML";
        $text = "The text";
        $lang = "The Language";
        
        $headers = [
            'User-Agent' => UserAgentsHelper::getUserAgent()
        ];
        $metadata = [];
        
        //httpclient
        $httpclient = $this
                ->getMockBuilder(HTTPClient::class)
                ->getMock();
        $httpclient
                ->expects($this->once())
                ->method('get')
                ->with($url, $headers)
                ->willReturn($html);
        
        //htmlextractor
        $htmlextractor = $this
                ->getMockBuilder(HTMLTextExtractor::class)
                ->getMock();
        
        $htmlextractor
                ->expects($this->once())
                ->method('extractText')
                ->with($html)
                ->willReturn($text);  
        
        //languagedetector
        $languagedetector = $this
                ->getMockBuilder(LanguageDetector::class)
                ->getMock();
        
        $languagedetector
                ->expects($this->once())
                ->method('detectLanguage')
                ->with($text)
                ->willReturn($lang);
        
        $loader = new DefaultArticleLoader($httpclient, $languagedetector, $htmlextractor);
        
        $article = $loader->loadArticle($url);
        
        self::assertSame($text, $article->getText());
        self::assertSame($lang, $article->getLanguage());
        self::assertSame($metadata, $article->getMetadata());
        //self::assertSame($text, $article->getTitle());
        self::assertSame($url, $article->getUrl());
    }

}