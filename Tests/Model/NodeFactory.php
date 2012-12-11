<?php

namespace Goutte\TreeBundle\Tests\Model;

use Goutte\TreeBundle\Factory\NodeFactory as AbstractNodeFactory;

class NodeFactory extends AbstractNodeFactory
{
    public function getClass()
    {
        return 'Goutte\TreeBundle\Tests\Model\Node';
    }
}