<?php

namespace NachoBrito\TTBot\Article\Domain;

/**
 *
 * @author nacho
 */
interface HTMLTextExtractor {
    /**
     * 
     * @param string $html
     * @return string
     */
    public function extractText(string $html):string;
}
