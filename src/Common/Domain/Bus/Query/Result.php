<?php declare( strict_types=1 );

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
     * @return Traversable<mixed>
     */
    public function getItems(): Traversable;
}
