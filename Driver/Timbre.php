<?php

namespace Goutte\TreeBundle\Driver;

use Goutte\TreeBundle\Is\Driver;
use Goutte\TreeBundle\Is\NodeFactory;
use Goutte\TreeBundle\Is\ValuedNode;

/**
 * Driver (quite a dumb one, I'm afraid) for Timbre.js (http://mohayonao.github.com/timbre/)
 *
 * Notes :
 * - Numeric values need to be encapsulated in T(), as in T("+",T(6),T(7)) instead of T("+",6,7)
 * - No spaces
 */
class Timbre extends StringUtilsDriver implements Driver
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

        $value = (string) $node->getValue();

        if ($this->isNumeric($value) || $this->isBoolean($value)) {
            $s = "T({$value})";
        } else {
            $s  = 'T(';
            $s .= '"'.$value.'"';
            if (!empty($children)) {
                $s .= ',';
                $s .= implode(',',$children);
            }
            $s .= ')';
        }

        return $s;
    }

    public function stringToNode($string)
    {
        $matches = array();
        if (!preg_match('!^T\("?([^,"]+)"?,?(.*)\)$!', $string, $matches)) {
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