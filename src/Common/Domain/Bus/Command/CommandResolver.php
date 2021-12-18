<?php declare( strict_types=1 );

namespace NachoBrito\TTBot\Common\Domain\Bus\Command;

/**
 *
 * @author nacho
 */
interface CommandResolver {

    /**
     * 
     * @param iterable<CommandHandler> $handlers
     * @return array<string,array<int, CommandHandler>>
     */
    public function buildHandlersMap(iterable $handlers):array;

    /**
     * 
     * @param Command $command
     * @return string
     */
    public function getHandlerClass(Command $command): string;

    /**
     * 
     * @param CommandHandler $handler
     * @return string
     */
    public function getCommandClass(CommandHandler $handler): string;
}
