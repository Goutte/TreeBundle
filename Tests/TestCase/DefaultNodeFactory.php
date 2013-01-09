<?php

namespace Goutte\TreeBundle\Tests\TestCase;

use Goutte\TreeBundle\Model\Node;

trait DefaultNodeFactory
{
    public function createNodeFromLabel($label='')
    {
        $node = new Node();
        $node->setLabel($label);

        return $node;
    }
}