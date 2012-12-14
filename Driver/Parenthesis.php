<?php

namespace Goutte\TreeBundle\Driver;

use Goutte\TreeBundle\Exception\DriverException;
use Goutte\TreeBundle\Is\Driver as DriverInterface;
use Goutte\TreeBundle\Is\NodeFactory;
use Goutte\TreeBundle\Is\Node;

/**
 * Simple parenthesis driver, for strings like so: Root(ChildA(),ChildB(ChildBA(),ChildBB()))
 *
 * Notes :
 * - No spaces
 */
class Parenthesis extends StringUtilsDriver implements DriverInterface
{
    protected $nodeClass;

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
            throw new DriverException("Cannot convert '{$string}' to node.");
        } else {
            $value = $matches[1];
            $children = $matches[2];
            /** @var $node Node */
            $node = new $this->nodeClass;
            $node->setValue($value);

            foreach ($this->explode($children) as $childString)
            {
                $node->addChild($this->stringToNode($childString));
            }

            return $node;
        }
    }

    public function getName()
    {
        return 'parenthesis';
    }
}