<?php

namespace Goutte\TreeBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Goutte\TreeBundle\DependencyInjection\DriverCompilerPass;

class GoutteTreeBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new DriverCompilerPass());
    }
}