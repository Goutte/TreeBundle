<?php

namespace Goutte\TreeBundle\Driver;

use Goutte\TreeBundle\Exception\DriverException;
use Goutte\TreeBundle\Is\Driver as DriverInterface;
use Goutte\TreeBundle\Is\Node;

/**
 * Driver (quite a dumb one, I'm afraid) for Timbre.js (http://mohayonao.github.com/timbre/)
 *
 * Notes :
 * - Numeric values need to be encapsulated in T(), as in T("+",T(6),T(7)) instead of T("+",6,7)
 * - No spaces
 */
class Timbre extends StringUtilsDriver implements DriverInterface
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
}