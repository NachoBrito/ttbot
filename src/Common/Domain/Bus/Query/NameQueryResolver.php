<?php

namespace NachoBrito\TTBot\Common\Domain\Bus\Query;

/**
 * Description of NameQueryResolver
 *
 * @author nacho
 */
class NameQueryResolver implements QueryResolver {


    /**
     * 
     * @param Query $query
     * @return string
     */
    public function getHandlerClass(Query $query): string {
        $query_class = get_class($query);

        $this->validateQueryClass($query_class);

        $handler_class = preg_replace('/Query$/', 'Handler', $query_class);

        return $handler_class;
    }

    /**
     * 
     * @param type $query_class
     */
    private function validateQueryClass($query_class) {
        if (!preg_match('/Query$/', $query_class)) {
            throw new InvalidQueryException("Class $query_class is not a valid query class name. Suffix 'Query' is mandatory.");
        }
    }
    
    /**
     * 
     * @param type $handler_class
     */
    private function validateHandlerClass($handler_class) {
        if (!preg_match('/Handler$/', $handler_class)) {
            throw new InvalidHandlerException("Class $handler_class is not a valid query handler class name. Suffix 'Handler' is mandatory.");
        }
    }

    /**
     * 
     * @param iterable $handlers
     */
    public function buildHandlersMap(iterable $handlers) {
        $map = [];
        foreach($handlers as $handler)
        {
            $query_class = $this->getQueryClass($handler);
            
            $map[$query_class] = [$handler];
        }
        return $map;
    }

    /**
     * 
     * @param QueryHandler $handler
     * @return string
     */
    public function getQueryClass(QueryHandler $handler): string {
        $handler_class = get_class($handler);

        $this->validateHandlerClass($handler_class);

        $query_class = preg_replace('/Handler$/', 'Query', $handler_class);

        return $query_class;          
    }

}
