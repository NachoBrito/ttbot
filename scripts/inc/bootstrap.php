<?php

declare( strict_types=1 );

use NachoBrito\TTBot\Common\Domain\ConfigLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

require_once __DIR__ . "/../../vendor/autoload.php";

define('VENDOR_DIR', __DIR__ . "/../../vendor");

class ContainerProvider {

    /**
     * 
     * @var ContainerBuilder
     */
    private static $container = NULL;

    /**
     * 
     * @return ContainerBuilder
     */
    public static function getContainer(): ContainerBuilder {
        if (!self::$container) {
            $containerBuilder = new ContainerBuilder();
            $loader = new PhpFileLoader($containerBuilder, new FileLocator(__DIR__));
            $loader->load('services.php');
            $containerBuilder->compile();
            
            self::$container = $containerBuilder;
            
            /** @var ConfigLoader $config */
            $config = self::$container->get(ConfigLoader::class);
            $config->load();
        }
        return self::$container;
    }

}

/**
 * 
 * @return ContainerBuilder
 */
function getContainer(): ContainerBuilder {
    return ContainerProvider::getContainer();
}
function convert($size)
{
    $unit=array('b','kb','mb','gb','tb','pb');
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}