<?php

namespace Goutte\TreeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('goutte_tree');

        $rootNode
            ->children()
                ->scalarNode('driver')
//                    ->defaultValue('')
//                    ->info('The namespaced Driver class to use when converting a tree from and to a string. '
//                          .'The Driver must implement Goutte\\TreeBundle\\Is\\Driver')
//                    ->example('MyVendor\\MyBundle\\Driver\\MyAmazingDriver')
                ->end()
            ->end();

        return $treeBuilder;
    }
}