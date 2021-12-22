<?php

declare( strict_types=1 );

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

require_once __DIR__ . "/../../vendor/autoload.php";

define('VENDOR_DIR', __DIR__ . "/../../vendor");

/**
 * 
 * @return ContainerBuilder
 */
function getContainer(): ContainerBuilder {
    $containerBuilder = new ContainerBuilder();
    $loader = new PhpFileLoader($containerBuilder, new FileLocator(__DIR__));
    $loader->load('services.php');
    $containerBuilder->compile();
    return $containerBuilder;
}
