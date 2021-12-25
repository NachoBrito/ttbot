#!/usr/bin/php
<?php

use NachoBrito\TTBot\Common\Domain\Bus\Query\QueryBus;
use NachoBrito\TTBot\Twitter\Application\MentionsQuery;
use Symfony\Component\DependencyInjection\ContainerBuilder;

require_once __DIR__ . '/inc/bootstrap.php';

$query = new MentionsQuery;

/** @var QueryBus $bus */
/** @var ContainerBuilder $container */
echo "Get container\n";
$container = getContainer();
echo "Get query bus\n";
$bus = $container->get(QueryBus::class);
echo "Execute query\n";
$result = $bus->ask($query);
echo "print results\n";
print_r($result->getItems());
