<?php

declare( strict_types=1 );

namespace NachoBrito\TTBot\Common\Domain\Bus\Query;

use ArrayObject;
use Traversable;

/**
 * 
 *
 * @author nacho
 */
class QueryResult implements Result{
    
    /**
     * 
     * @var Query
     */
    private $query;
    
    /**
     * 
     * @var ArrayObject
     */
    private $items;
    
    /**
     * 
     * @param Query $query
     * @param array<mixed> $items
     */
    public function __construct(Query $query, array $items) {
        $this->query = $query;
        $this->items = new ArrayObject($items);
    }

    
    /**
     * 
     * @return Traversable<mixed>
     */
    public function getItems(): Traversable {
        return $this->items;
    }

    /**
     * 
     * @return Query
     */
    public function getQuery(): Query {
        return $this->query;
    }

}
