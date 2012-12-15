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

class Ascii implements DriverInterface
{
    public function __construct($nodeClass)
    {
        $this->nodeClass = $nodeClass;
    }

    public function nodeToString(Node $node)
    {
        return implode("\n",$this->nodeToStringArray($node));
    }

    public function stringToNode($string)
    {
        $string = trim($string);

        if ('' == $string) {
            return null;
        }

        $array = explode("\n", $string); // may not play nice with REALLY BIG trees

        return $this->stringArrayToNode($array);
    }

    public function nodeToStringArray(Node $node)
    {
        $children = array();
        foreach ($node->getChildren() as $child) {
            $children[] = $this->nodeToStringArray($child);
        }

        $s = array($node->getValue());

        if (empty($children)) {
            return $s;
        }

        // first 3 columns
        foreach ($children as $child) {
            $s[] = '+--';
            for ($i=0; $i<count($child)-1; $i++) {
                if (1 < count($children)) {
                    $s[] = '|  ';
                } else {
                    $s[] = '   ';
                }
            }
        }

        // children trees
        $j = 1;
        foreach ($children as $child) {
            for ($i=0; $i<count($child); $i++) {
                $s[$j++] .= $child[$i];
            }
        }

        return $s;
    }

    /**
     * Creates root node of provided subtree, and recurse with children's arrays by removing the first 3 characters
     * @param $array
     * @return \Goutte\TreeBundle\Is\Node
     */
    public function stringArrayToNode($array)
    {
        /** @var $node Node */
        $node = new $this->nodeClass;
        $node->setValue(trim($array[0]));

        $childArray = array();
        for ($i=1; $i<count($array); $i++) {
            if ('+' === $array[$i]{0}) {
                if (!empty($childArray)) {
                    $node->addChild($this->stringArrayToNode($childArray));
                }
                $childArray = array(substr($array[$i], 3));
            } else {
                $childArray[] = substr($array[$i], 3);
            }
        }
        if (!empty($childArray)) {
            $node->addChild($this->stringArrayToNode($childArray));
        }

        return $node;
    }
}