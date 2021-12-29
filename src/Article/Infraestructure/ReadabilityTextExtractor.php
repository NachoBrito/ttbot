<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Article\Infraestructure;

use andreskrey\Readability\Configuration;
use andreskrey\Readability\Readability;
use NachoBrito\TTBot\Article\Domain\HTMLTextExtractor;

/**
 * 
 * @codeCoverageIgnore
 * @author nacho
 */
class ReadabilityTextExtractor implements HTMLTextExtractor{
    
    /**
     * 
     * @param string $html
     * @return string
     */
    public function extractText(string $html): string {
        $configuration = new Configuration();
        $readability = new Readability($configuration);
        $readability->parse($html);
        $content = $readability->getContent();
        
        return $content;
    }

}
