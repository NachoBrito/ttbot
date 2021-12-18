<?php declare( strict_types=1 ); 
namespace NachoBrito\TTBot\Article\Domain;

interface ArticleSummarizer
{
    public function summarize(Article $article): ArticleSummary;
}