#!/usr/bin/php
<?php

use NachoBrito\TTBot\Article\Application\SummarizeUrlCommand;
use NachoBrito\TTBot\Common\Domain\Bus\Command\CommandBus;
use Symfony\Component\DependencyInjection\ContainerBuilder;

require_once __DIR__ . '/inc/bootstrap.php';

$url = $argv[1];
echo "Sumarizing $url\n";

$cmd = new SummarizeUrlCommand($url);

/** @var CommandBus $bus */
/** @var ContainerBuilder $container */
echo "Building container: " . convert(memory_get_usage(true)) . "\n";
$container = getContainer();
echo "Container built. Memory used: " . convert(memory_get_usage(true)) . "\n";
$def = $container->getDefinition(CommandBus::class);
//die(print_r($def, TRUE));
$bus = $container->get(CommandBus::class);
echo "Got command bus. Memory used: " . convert(memory_get_usage(true)) . "\n";
$bus->dispatch($cmd);
