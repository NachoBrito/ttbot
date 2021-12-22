<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Common\Infraestructure;

use NachoBrito\TTBot\Common\Domain\LanguageDetector;
use LanguageDetector\LanguageDetector as LRDetector;

/**
 * 
 *
 * @author nacho
 */
class LandrokLanguageDetector implements LanguageDetector {

    /**
     * 
     * @param string $text
     * @return string
     */
    public function detectLanguage(string $text): string {
        $detector = new LRDetector();

        $language = $detector->evaluate($text)->getLanguage();
        
        return $language->getCode();
    }

}
