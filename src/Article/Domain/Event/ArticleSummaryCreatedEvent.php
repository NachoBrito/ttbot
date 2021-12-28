<?php declare( strict_types=1 ); 
namespace NachoBrito\TTBot\Article\Domain\Event;

use NachoBrito\TTBot\Article\Domain\Model\ArticleSummary;
use NachoBrito\TTBot\Common\Domain\Bus\Event\Event;


class ArticleSummaryCreatedEvent implements Event
{
    /**
     * Article summary
     *
     * @var ArticleSummary
     */ 
    protected $summary;

    public function __construct(ArticleSummary $summary)
    {
        $this->summary = $summary;
    }
    

    /**
     * 
     * @return ArticleSummary
     */
    public function getSummary(): ArticleSummary
    {
        return $this->summary;
    }
}