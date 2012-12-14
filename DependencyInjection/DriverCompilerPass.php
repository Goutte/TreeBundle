<?php

namespace Goutte\TreeBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class DriverCompilerPass implements CompilerPassInterface
{
    const SERVICE_NAME = 'goutte_tree.serializer';
    const SERVICE_TAG  = 'goutte_tree.driver';

    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(self::SERVICE_NAME)) {
            return;
        }

        $definition = $container->getDefinition(self::SERVICE_NAME);

        $taggedServices = $container->findTaggedServiceIds(self::SERVICE_TAG);
        foreach ($taggedServices as $id => $attributes) {
            $definition->addMethodCall(
                'addDriver',
                array(new Reference($id))
            );
        }
    }
}