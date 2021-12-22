<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Common\Infraestructure;

use LanguageDetector\LanguageDetector as LRDetector;
use NachoBrito\TTBot\Article\Domain\Model\Language;
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
        $languages = Language::all();
        $detector = new LRDetector(NULL, $languages);
        $language = $detector->evaluate($text)->getLanguage();
        
        return $language->getCode();
    }

}
