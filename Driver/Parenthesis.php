<?php

namespace Goutte\TreeBundle\Driver;

use Goutte\TreeBundle\Is\Driver;
use Goutte\TreeBundle\Is\NodeFactory;
use Goutte\TreeBundle\Is\ValuedNode;


class Parenthesis implements Driver
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

    protected function explode($string)
    {
        $depth = 0;
        $array = array();
        $positions = array();
        for ($i=0;$i<strlen($string);$i++)
        {
            switch ($string{$i})
            {
                case '(':
                    $depth++;
                    break;
                case ')':
                    $depth--;
                    break;
                case ',':
                    if (0 == $depth) {
                        $positions[] = $i;
                    }
                    break;
            }
        }

        $child = '';
        for ($i=0;$i<strlen($string);$i++)
        {
            if (in_array($i, $positions)) {
                $array[] = $child;
                $child = '';
            } else {
                $child .= $string{$i};
            }
        }
        if ('' != $child) {
            $array[] = $child;
        }

        return $array;
    }
}