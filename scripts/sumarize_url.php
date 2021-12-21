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
$container = getContainer();
print_r($container->getAliases());
$bus = $container->get(CommandBus::class);

$bus->dispatch($cmd);
