<?php

declare( strict_types=1 );


namespace NachoBrito\TTBot\Article\Infraestructure;

use NachoBrito\TTBot\Article\Domain\HTMLTextExtractor;


/**
 * 
 * @codeCoverageIgnore
 * @author nacho
 */
class HTML2TextExtractor implements HTMLTextExtractor{
    
    /**
     * 
     * @param string $html
     * @return string
     */
    public function extractText(string $html): string {
        
        $o = new CustomHTML2Text($html, [
            'do_links' => 'none',
            'width' => 0
        ]);    
        
        return $o->getText();
    }

}
