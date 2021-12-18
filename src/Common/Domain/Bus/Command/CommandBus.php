<?php

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
