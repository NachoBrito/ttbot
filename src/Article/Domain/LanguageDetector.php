<?php

namespace NachoBrito\TTBot\Article\Domain;

/**
 *
 * @author nacho
 */
interface LanguageDetector {


    /**
     * 
     * @param string $text
     * @return string
     */
    public function detectLanguage(string $text): string;
}
