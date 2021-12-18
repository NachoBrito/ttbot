<?php declare( strict_types=1 );

namespace NachoBrito\TTBot\Common\Domain\Bus\Command;

/**
 *
 * @author nacho
 */
interface CommandBus {
    /**
     * 
     * @param Command $command
     * @return void
     */
    public function dispatch(Command $command):void;
}
