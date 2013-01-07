<?php

namespace Goutte\TreeBundle\Driver;

use Goutte\TreeBundle\Exception\DriverException;
use Goutte\TreeBundle\Factory\NodeFactoryInterface;
use Goutte\TreeBundle\Is\Driver as DriverInterface;
use Goutte\TreeBundle\Is\Node;

/*

A
+--B
|  +--C
|  +--D
|     +--G
+--F

*/

/**
 * Driver for Ascii trees like the one above
 *
 * Notes:
 * - (un)escapes linebreaks in values
 */
class Ascii implements DriverInterface
{
    protected $factory;

    public function __construct(NodeFactoryInterface $factory)
    {
        $this->factory = $factory;
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

    /**
     * @param Node $node
     * @return string[]
     */
    public function nodeToStringArray(Node $node)
    {
        $children = array();
        foreach ($node->getChildren() as $child) {
            $children[] = $this->nodeToStringArray($child);
        }

        $array = array($this->escape($node->getLabel()));

        if (empty($children)) {
            return $array;
        }

        // first 3 columns
        foreach ($children as $kChild => $child) {
            $array[] = '+--';
            for ($i = 0 ; $i < count($child)-1 ; $i++) {
                if ($kChild+1 < count($children)) {
                    $array[] = '|  ';
                } else {
                    $array[] = '   ';
                }
            }
        }

        // children trees
        $j = 1;
        foreach ($children as $child) {
            for ($i = 0 ; $i < count($child) ; $i++) {
                $array[$j++] .= $child[$i];
            }
        }

        return $array;
    }

    /**
     * Creates root node of provided subtree, and recurse with children's arrays by removing the first 3 characters
     * @param string[] $array
     * @return Node
     */
    public function stringArrayToNode($array)
    {
        $node = $this->factory->createNodeFromLabel($this->unescape($array[0]));

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

    protected function escape($string)
    {
        $linebreaks = array("\r\n","\r","\n");
        $string = str_replace($linebreaks,'\n',$string);
        return trim($string);
    }

    protected function unescape($string)
    {
        $string = str_replace('\n',"\n",$string);
        return trim($string);
    }


}