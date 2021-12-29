<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Article\Infraestructure;

use NachoBrito\TTBot\Article\Domain\URLExtractor;
use VStelmakh\UrlHighlight\UrlHighlight;

/**
 * 
 * @codeCoverageIgnore
 * @author nacho
 */
class StelmakhURLExtractor implements URLExtractor {

    /**
     * 
     * @param string $input
     * @return array
     */
    public function getUrls(string $input): array {
        $urlHighlight = new UrlHighlight();
        return $urlHighlight->getUrls($input);
    }

}
