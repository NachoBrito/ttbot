<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Article\Domain;

class ArticleSummary {

    /**
     * The original article
     *
     * @var Article
     */
    protected $article;

    /**
     * The summary sentences
     *
     * @var array<string>
     */
    protected $sentences;

    /**
     * 
     * @param Article $article
     * @param array<string> $sentences
     */
    public function __construct(Article $article, array $sentences) {
        $this->article = $article;
        $this->sentences = $sentences;
    }

    /**
     * Get the original article
     *
     * @return  Article
     */
    public function getArticle() {
        return $this->article;
    }

    /**
     * Set the original article
     *
     * @param  Article  $article  The original article
     *
     * @return  self
     */
    public function setArticle(Article $article) {
        $this->article = $article;

        return $this;
    }

    /**
     * Get the summary sentences
     *
     * @return  array<string>
     */
    public function getSentences() {
        return $this->sentences;
    }

    /**
     * Set the summary sentences
     *
     * @param  array<string>  $sentences  The summary sentences
     *
     * @return  self
     */
    public function setSentences(array $sentences): ArticleSummary {
        $this->sentences = $sentences;

        return $this;
    }

}
