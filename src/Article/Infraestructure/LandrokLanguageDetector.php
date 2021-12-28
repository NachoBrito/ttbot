<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Article\Infraestructure;

use LanguageDetector\LanguageDetector as LRDetector;
use NachoBrito\TTBot\Article\Domain\LanguageDetector;
use NachoBrito\TTBot\Article\Domain\Model\Language;


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
        $languages = Language::all();
        $detector = new LRDetector(NULL, $languages);
        $language = $detector->evaluate($text)->getLanguage();
        
        return $language->getCode();
    }

}
