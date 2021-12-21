<?php

namespace NachoBrito\TTBot\Common\Domain\Bus\Event;

use ReflectionClass;
use ReflectionParameter;

/**
 * Description of CallableArgsEventResolver
 *
 * @author nacho
 */
class CallableArgsEventResolver implements EventResolver {

    /**
     * 
     * @param iterable<EventSubscriber> $subscribers
     * @return array<string,array<int, EventSubscriber>>
     */
    public function buildSubscribersMap(iterable $subscribers):array {
        $map = [];

        foreach ($subscribers as $subscriber) {
            $subscriber_class = get_class($subscriber);
            $class = new ReflectionClass($subscriber_class);
            $method = $class->getMethod('__invoke');
            $params = $method->getParameters();
            /** @var ReflectionParameter $first_param */
            $first_param = $params[0];
            $event_name = $first_param->getClass()->getName();
            
            $map[$event_name][] = $subscriber_class;
        }
        
        return $map;
    }

}
