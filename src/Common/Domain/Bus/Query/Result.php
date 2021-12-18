<?php

namespace NachoBrito\TTBot\Common\Domain\Bus\Query;

use Traversable;

/**
 *
 * @author nacho
 */
interface Result {
    /**
     * 
     * @return Query
     */
    public function getQuery(): Query;
    
    /**
     * 
     * @return array
     */
    public function getItems(): Traversable;
}
