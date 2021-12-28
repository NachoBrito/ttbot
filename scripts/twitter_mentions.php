#!/usr/bin/php
<?php

use NachoBrito\TTBot\Article\Application\HandleTwitterSummarizeRequestsCommand;
use NachoBrito\TTBot\Common\Domain\Bus\Command\CommandBus;
use Symfony\Component\DependencyInjection\ContainerBuilder;

require_once __DIR__ . '/inc/bootstrap.php';

$cmd = new HandleTwitterSummarizeRequestsCommand();

/** @var CommandBus $bus */
/** @var ContainerBuilder $container */
$container = getContainer();
$bus = $container->get(CommandBus::class);

$bus->dispatch($cmd);