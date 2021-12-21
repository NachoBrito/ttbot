<?php

declare( strict_types=1 );


namespace NachoBrito\TTBot\Article\Application;

use NachoBrito\TTBot\Common\Domain\Bus\Command\Command;

/**
 * 
 *
 * @author nacho
 */
class SummarizeUrlCommand implements Command{
    /**
     * 
     * @var string
     */
    private $url;
    
    public function __construct(string $url) {
        $this->url = $url;
    }
    
    public function getUrl(): string {
        return $this->url;
    }



}
