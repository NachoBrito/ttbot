<?php

declare( strict_types=1 );


namespace NachoBrito\TTBot\Common\Domain\Model;

/**
 * 
 *
 * @author nacho
 */
class HTTPResponse {
    /**
     * 
     * @var array<string,string>
     */
    private $headers;
    
    /**
     * 
     * @var string
     */
    private $content;
    
    public function __construct($headers, string $content) {
        $this->headers = $headers;
        $this->content = $content;
    }

    public function getHeaders() {
        return $this->headers;
    }

    public function getContent(): string {
        return $this->content;
    }


}
