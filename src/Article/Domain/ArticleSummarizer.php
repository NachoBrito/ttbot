<?php declare( strict_types=1 ); 
namespace NachoBrito\TTBot\Article\Domain;

use NachoBrito\TTBot\Article\Domain\Model\Article;
use NachoBrito\TTBot\Article\Domain\Model\ArticleSummary;

interface ArticleSummarizer
{
    public function summarize(Article $article): ArticleSummary;
}