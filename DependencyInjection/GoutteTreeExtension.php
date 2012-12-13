<?php

namespace Goutte\TreeBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class GoutteTreeExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $container->setParameter('goutte_tree.driver_type', $config['driver']);

        // fixme : do something with the configuration
    }

//    /**
//     * Returns the base path for the XSD files.
//     *
//     * @return string The XSD base path
//     */
//    public function getXsdValidationBasePath()
//    {
//        return __DIR__.'/../Resources/config/schema';
//    }
//
//    public function getNamespace()
//    {
//        return 'http://symfony.com/schema/dic/goutte_tree';
//    }
}