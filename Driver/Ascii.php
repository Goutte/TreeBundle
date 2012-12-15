<?php

namespace Goutte\TreeBundle\Driver;

use Goutte\TreeBundle\Exception\DriverException;
use Goutte\TreeBundle\Is\Driver as DriverInterface;
use Goutte\TreeBundle\Is\Node;

/*

A
+--B
|  +--C
|  +--D
|  |  +--G
|  +--E
+--F

*/

class Ascii extends StringUtilsDriver implements DriverInterface
{
    public function __construct($nodeClass)
    {
        $this->nodeClass = $nodeClass;
    }

    public function nodeToString(Node $node)
    {
        $children = array();
        foreach ($node->getChildren() as $child) {
            $children[] = $this->nodeToString($child);
        }

        $s = $node->getValue();

        return $s;
    }

    public function stringToNode($string)
    {

        /** @var $node Node */
        $node = new $this->nodeClass;
        $node->setValue($string);

        return $node;

    }
}