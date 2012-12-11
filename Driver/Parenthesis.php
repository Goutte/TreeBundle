<?php

namespace Goutte\TreeBundle\Driver;

use Goutte\TreeBundle\Is\Driver;
use Goutte\TreeBundle\Is\NodeFactory;
use Goutte\TreeBundle\Is\ValuedNode;

/**
 * Simple parenthesis driver, for strings like so: Root(ChildA(),ChildB(ChildBA(),ChildBB()))
 *
 * Notes :
 * - No spaces
 */
class Parenthesis extends StringUtilsDriver implements Driver
{
    protected $factory;

    public function __construct(NodeFactory $factory)
    {
        $this->factory = $factory;
    }

    public function nodeToString(ValuedNode $node)
    {
        $children = array();
        foreach ($node->getChildren() as $child) {
            $children[] = $this->nodeToString($child);
        }

        $s = (string) $node->getValue();
        $s .= '(';
        $s .= implode(',',$children);
        $s .= ')';

        return $s;
    }

    public function stringToNode($string)
    {
        $matches = array();
        if (!preg_match("!^([^(]+)\((.*)\)$!", $string, $matches)) {
            throw new \Exception("Cannot convert '{$string}' to node.");
        } else {
            $value = $matches[1];
            $children = $matches[2];
            /** @var $node ValuedNode */
            $node = $this->factory->createNode();
            $node->setValue($value);

            foreach ($this->explode($children) as $childString)
            {
                $node->addChild($this->stringToNode($childString));
            }

            return $node;
        }
    }

}