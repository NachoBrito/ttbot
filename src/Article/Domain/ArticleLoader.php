<?php declare( strict_types=1 ); 
namespace NachoBrito\TTBot\Article\Domain;

use NachoBrito\TTBot\Article\Domain\Model\Article;

interface ArticleLoader
{
    public function loadArticle(string $uri):Article;
}