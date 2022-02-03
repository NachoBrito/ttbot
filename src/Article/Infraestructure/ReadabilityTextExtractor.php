<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Article\Infraestructure;

use andreskrey\Readability\Configuration;
use andreskrey\Readability\Readability;
use Exception;
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
        
        if(!$content)
        {
            throw new Exception("Empty output from Readability for input markup!");
        }
        return $content;
    }

}
