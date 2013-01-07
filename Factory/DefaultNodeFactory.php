<?php

namespace Goutte\TreeBundle\Factory;

use Goutte\TreeBundle\Is\Node;

class DefaultNodeFactory implements NodeFactoryInterface
{
    protected $nodeClass;

    public function __construct($nodeClass)
    {
        // todo: check existence of class $nodeClass
        $this->nodeClass = $nodeClass;
    }

    public function createNodeFromLabel($label)
    {
        /** @var $node Node */
        $node = new $this->nodeClass;
        $node->setLabel($label);

        return $node;
    }
}