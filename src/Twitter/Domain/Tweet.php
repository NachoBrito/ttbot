<?php

declare( strict_types=1 );


namespace NachoBrito\TTBot\Twitter\Domain;

use DateTime;

/**
 * 
 *
 * @author nacho
 */
class Tweet {
    /**
     * 
     * @var string
     */
    private $id;   
    
    /**
     * 
     * @var string
     */
    private $lang;
    
    /**
     * 
     * @var string
     */
    private $text;
    
    /**
     * 
     * @var string
     */
    private $author_id;
    
    /**
     * 
     * @var string
     */    
    private $author_name;
    
    /**
     * 
     * @var string
     */    
    private $author_username;
    
    /**
     * 
     * @var DateTime
     */    
    private $created_at;
    
    public function getId(): string {
        return $this->id;
    }

    public function getLang(): string {
        return $this->lang;
    }

    public function getText(): string {
        return $this->text;
    }


    public function getAuthorName(): string {
        return $this->author_name;
    }

    public function getAuthorUsername(): string {
        return $this->author_username;
    }

    public function getCreatedAt(): DateTime {
        return $this->created_at;
    }

    public function setId(string $id) {
        $this->id = $id;
        return $this;
    }

    public function setLang(string $lang) {
        $this->lang = $lang;
        return $this;
    }

    public function setText(string $text) {
        $this->text = $text;
        return $this;
    }


    public function setAuthorName(string $author_name) {
        $this->author_name = $author_name;
        return $this;
    }

    public function setAuthorUsername(string $author_username) {
        $this->author_username = $author_username;
        return $this;
    }

    public function setCreatedAt(DateTime $created_at) {
        $this->created_at = $created_at;
        return $this;
    }

    public function getAuthorId(): string {
        return $this->author_id;
    }

    public function setAuthorId(string $author_id) {
        $this->author_id = $author_id;
        return $this;
    }



}
