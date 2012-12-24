<?php

namespace Goutte\TreeBundle\Driver;

use Goutte\TreeBundle\Exception\DriverException;
use Goutte\TreeBundle\Is\Driver as DriverInterface;
use Goutte\TreeBundle\Is\Node;

/**
 * Smarter parenthesis driver, for strings like so: Root(ChildA,ChildB(ChildBA,ChildBB))
 *
 * Notes :
 * - A node with no children will not have an empty parenthesis
 * - Superfluous spaces will be trimmed
 * - Special characters `(`, `)` and `,` in the values will be (un)escaped automatically
 */
class Parenthesis extends StringUtilsDriver implements DriverInterface
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

        $s = $this->escapeValue($node);
        if (!empty($children)) {
            $s .= '(';
            $s .= implode(',',$children);
            $s .= ')';
        }

        return $s;
    }

    public function stringToNode($string)
    {
        $matches = array();
        if (!preg_match("!^(?P<value>(?:\\\\\(|\\\\\)|[^(])+)(?:\((?P<children>.*)\))?$!", $string, $matches)) {
            throw new DriverException("Cannot convert '{$string}' to node.");
        } else {
            /** @var $node Node */
            $node = new $this->nodeClass;
            $node->setLabel($this->unescapeValue($matches['value']));

            $children = !empty($matches['children']) ? trim($matches['children']) : '';
            foreach ($this->explode($children) as $childString)
            {
                $node->addChild($this->stringToNode($childString));
            }

            return $node;
        }
    }

    protected function escapeValue(Node $node)
    {
        $s = (string) $node->getLabel();
        $s = str_replace('(','\(',$s);
        $s = str_replace(')','\)',$s);
        $s = str_replace(',','\,',$s);
        return trim($s);
    }

    protected function unescapeValue($s)
    {
        $s = str_replace('\(','(',$s);
        $s = str_replace('\)',')',$s);
        $s = str_replace('\,',',',$s);
        return trim($s);
    }
}