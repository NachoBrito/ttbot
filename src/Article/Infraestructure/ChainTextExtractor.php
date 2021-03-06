<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Article\Infraestructure;

use Exception;
use NachoBrito\TTBot\Article\Domain\HTMLTextExtractor;
use NachoBrito\TTBot\Common\Domain\Logger;

/**
 * 
 * @codeCoverageIgnore
 * @author nacho
 */
class ChainTextExtractor implements HTMLTextExtractor {

    /**
     * 
     * @var ReadabilityTextExtractor
     */
    private $readability;

    /**
     * 
     * @var HTML2TextExtractor
     */
    private $html2text;

    /**
     * 
     * @var Logger
     */
    private $logger;

    public function __construct(ReadabilityTextExtractor $readability, HTML2TextExtractor $html2text, Logger $logger) {
        $this->readability = $readability;
        $this->html2text = $html2text;
        $this->logger = $logger;
    }

    /**
     * 
     * @param string $html
     * @return string
     */
    public function extractText(string $html): string {

        try {
            $this->logger->debug("Calling Readability extractor");
            $content = $this->readability->extractText($html);
        } catch (Exception $ex) {
            $msg = "Readability failed: {$ex->getMessage()}. Will pass raw html to HTML2Text";
            $this->logger->error($msg);
            $content = $html;
        }

        $this->logger->debug("Calling HTML2Text extractor");
        $text = $this->html2text->extractText($content);

        $decoded_entities = html_entity_decode($text);
                
        return $decoded_entities;
    }

}
