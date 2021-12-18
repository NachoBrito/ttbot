<?php declare( strict_types=1 ); 
namespace NachoBrito\TTBot\Article\Application;

use NachoBrito\TTBot\Article\Domain\Article;
use NachoBrito\TTBot\Common\Domain\Bus\Command\Command;

class SummarizeArticleCommand implements Command
{
    /**
     * The article to summarize
     *
     * @var Article
     */
    private $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }


    /**
     * Get the article to summarize
     *
     * @return  Article
     */ 
    public function getArticle():Article
    {
        return $this->article;
    }
}