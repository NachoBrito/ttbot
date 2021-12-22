<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Article\Infraestructure;

use NachoBrito\TTBot\Article\Domain\ArticleLoader;
use NachoBrito\TTBot\Article\Domain\HTMLTextExtractor;
use NachoBrito\TTBot\Article\Domain\LanguageDetector;
use NachoBrito\TTBot\Article\Domain\Model\Article;
use NachoBrito\TTBot\Common\Domain\HTTPClient;
use NachoBrito\TTBot\Common\Domain\LoggerInterface;
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

    /**
     * 
     * @var LoggerInterface
     */
    private $logger;

    /**
     * 
     * @param HTTPClient $httpClient
     * @param LanguageDetector $languageDetector
     * @param HTMLTextExtractor $htmlExtractor
     * @param LoggerInterface $logger
     */
    public function __construct(HTTPClient $httpClient, LanguageDetector $languageDetector, HTMLTextExtractor $htmlExtractor, LoggerInterface $logger) {
        $this->httpClient = $httpClient;
        $this->languageDetector = $languageDetector;
        $this->htmlExtractor = $htmlExtractor;
        $this->logger = $logger;
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

        $this->logger->debug("Text:\n$text");
        
        $headers = $response->getHeaders();
        if (isset($headers['Content-Language'])) {
            $this->logger->info("Content-Language header detected: " . json_encode($headers['Content-Language']));
            $language = $headers['Content-Language'][0];
        } else {
            $this->logger->info("Content-Language header not found, using language detector.");
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
