<?php

namespace NachoBrito\TTBot\Common\Domain\Bus\Event;

/**
 *
 * @author nacho
 */
interface EventResolver {
    /**
     * 
     * @param iterable<EventSubscriber> $subscribers
     * @return array<string,array<int, EventSubscriber>>
     */
    public function buildSubscribersMap(iterable $subscribers):array;

}
