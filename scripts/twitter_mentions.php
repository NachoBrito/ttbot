#!/usr/bin/php
<?php

use NachoBrito\TTBot\Common\Domain\Bus\Query\QueryBus;
use NachoBrito\TTBot\Twitter\Application\MentionsQuery;
use Symfony\Component\DependencyInjection\ContainerBuilder;

require_once __DIR__ . '/inc/bootstrap.php';

$query = new MentionsQuery;

/** @var QueryBus $bus */
/** @var ContainerBuilder $container */
$container = getContainer();
$bus = $container->get(QueryBus::class);

$result = $bus->ask($query);

print_r($result->getItems());
