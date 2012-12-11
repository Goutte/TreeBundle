<?php

namespace Goutte\TreeBundle\Factory;

use \Goutte\TreeBundle\Is\NodeFactory as NodeFactoryInterface;

abstract class NodeFactory implements NodeFactoryInterface
{

    public function createNode()
    {
        $class = $this->getClass();
        $node = new $class;

        return $node;
    }

}