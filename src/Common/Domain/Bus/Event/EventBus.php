<?php declare( strict_types=1 ); 
namespace NachoBrito\TTBot\Common\Domain\Bus\Event;



interface EventBus
{
    /**
     * Dispatches an event to registered listeners
     *
     * @param Event $event
     * @return void
     */
    public function dispatch(Event $event):void;
}