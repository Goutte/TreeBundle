<?php

namespace Goutte\TreeBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
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
        foreach ($taggedServices as $id => $tagAttributes) {
            $m = array(); // id of the service must start with 'goutte_tree.driver.' so we can extract the alias
            $regex = "!^".preg_quote(self::SERVICE_TAG.'.')."(?P<alias>.+)$!";
            if (!preg_match($regex, $id, $m)) {
                throw new InvalidArgumentException("The id of a service tagged '".self::SERVICE_TAG."' must validate '".
                                                   self::SERVICE_TAG.".{mydriveralias}', got '{$id}'");
            }

            foreach ($tagAttributes as $attributes) {
                $default = false;
                if (!empty($attributes['default'])) {
                    $default = filter_var($attributes['default'], FILTER_VALIDATE_BOOLEAN);
                }

                $definition->addMethodCall(
                    'addDriver',
                    array(new Reference($id), $m['alias'], $default)
                );
            }
        }
    }
}