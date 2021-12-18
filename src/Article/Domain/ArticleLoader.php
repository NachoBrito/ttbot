<?php declare( strict_types=1 ); 
namespace NachoBrito\TTBot\Article\Domain;

interface ArticleLoader
{
    public function loadArticle(string $uri):Article;
}