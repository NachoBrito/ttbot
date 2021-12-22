<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Article\Infraestructure;

use NachoBrito\TTBot\Article\Domain\ArticleLoader;
use NachoBrito\TTBot\Article\Domain\Model\Article;
use NachoBrito\TTBot\Common\Domain\HTMLTextExtractor;
use NachoBrito\TTBot\Common\Domain\HTTPClient;
use NachoBrito\TTBot\Common\Domain\LanguageDetector;
use NachoBrito\TTBot\Common\Domain\Model\HTTPResponse;
use NachoBrito\TTBot\Common\Domain\UserAgentsHelper;

/**
 * 
 *
 * @author nacho
 */
class DefaultArticleLoader implements ArticleLoader {

    /**
     * 
     * @var HTTPClient
     */
    private $httpClient;

    /**
     * 
     * @var LanguageDetector
     */
    private $languageDetector;

    /**
     * 
     * @var HTMLTextExtractor
     */
    private $htmlExtractor;

    public function __construct(HTTPClient $httpClient, LanguageDetector $languageDetector, HTMLTextExtractor $htmlExtractor) {
        $this->httpClient = $httpClient;
        $this->languageDetector = $languageDetector;
        $this->htmlExtractor = $htmlExtractor;
    }

    /**
     * 
     * @param string $uri
     * @return Article
     */
    public function loadArticle(string $uri): Article {
        /** @var HTTPResponse $response */
        $response = $this->httpClient->get($uri, [
            'User-Agent' => UserAgentsHelper::getUserAgent()
        ]);

        $text = $this->htmlExtractor->extractText($response->getContent());

        $headers = $response->getHeaders();
        if(isset($headers['Content-Language']))
        {
            $language = $headers['Content-Language'][0];
        }
        else
        {
            $language = $this->languageDetector->detectLanguage($text);
        }
        
        $metadata = [];

        $article = (new Article())
                ->setMetadata($metadata)
                ->setText($text)
                ->setUrl($uri)
                ->setLanguage($language)
        ;

        return $article;
    }

}
