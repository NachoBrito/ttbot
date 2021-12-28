<?php declare( strict_types=1 ); 
namespace NachoBrito\TTBot\Article\Domain\Model;

class Article
{
    /**
     * @var string
     */
    private $url;
    /**
     * @var string
     */
    private $title;
    /**
     * @var string
     */
    private $text;    
    /**
     * 
     * @var string
     */
    private $language;

    /**
     * @var array<string,string>
     */
    private $metadata;

    /**
     * Get the value of url
     *
     * @return  string
     */ 
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set the value of url
     *
     * @param  string  $url
     *
     * @return  self
     */ 
    public function setUrl(string $url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get the value of title
     *
     * @return  string
     */ 
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @param  string  $title
     *
     * @return  self
     */ 
    public function setTitle(string $title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of text
     *
     * @return  string
     */ 
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set the value of text
     *
     * @param  string  $text
     *
     * @return  self
     */ 
    public function setText(string $text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get the value of metadata
     *
     * @return  array<string,string>
     */ 
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * Set the value of metadata
     *
     * @param  array<string,string>  $metadata
     *
     * @return  self
     */ 
    public function setMetadata(array $metadata)
    {
        $this->metadata = $metadata;

        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getLanguage(): string {
        return $this->language;
    }

    public function setLanguage(string $language):Article {
        $this->language = $language;
        return $this;
    }


    
}