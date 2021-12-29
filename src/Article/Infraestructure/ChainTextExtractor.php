<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Article\Infraestructure;

use NachoBrito\TTBot\Article\Domain\HTMLTextExtractor;
use NachoBrito\TTBot\Common\Domain\Logger;

/**
 * 
 * @codeCoverageIgnore
 * @author nacho
 */
class ChainTextExtractor implements HTMLTextExtractor{
    
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
        $this->logger->debug("Calling Readability extractor");
        $content = $this->readability->extractText($html);
        $this->logger->debug("Calling HTML2Text extractor");
        $text = $this->html2text->extractText($content);
        return $text;
    }

}
