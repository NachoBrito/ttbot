<?php 
namespace NachoBrito\TTBot\Article\Domain;

interface ArticleLoader
{
    public function loadArticle(string $uri):Article;
}