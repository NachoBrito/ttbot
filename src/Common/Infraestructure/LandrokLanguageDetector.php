<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Common\Infraestructure;

use NachoBrito\TTBot\Common\Domain\LanguageDetector;

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
        $detector = new LanguageDetector\LanguageDetector();

        $language = $detector->evaluate($text)->getLanguage();
        
        return $language;
    }

}
