<?php

namespace NachoBrito\TTBot\Article\Domain;

/**
 *
 * @author nacho
 */
interface URLExtractor {
    /**
     * 
     * @param string $input
     * @return array<string>
     */
    public function getUrls(string $input):array;
}
