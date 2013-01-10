<?php

namespace Goutte\TreeBundle\Driver;

use Goutte\TreeBundle\Exception\DriverException;
use Goutte\TreeBundle\Factory\NodeFactoryInterface;
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
    protected $factory;

    public function __construct(NodeFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function treeToString(Node $tree)
    {
        $children = array();
        foreach ($tree->getChildren() as $child) {
            $children[] = $this->treeToString($child);
        }

        $s = $this->escapeValue($tree);
        if (!empty($children)) {
            $s .= '(';
            $s .= implode(',',$children);
            $s .= ')';
        }

        return $s;
    }

    public function stringToTree($string)
    {
        $matches = array();
        if (!preg_match("!^(?P<value>(?:\\\\\(|\\\\\)|[^(])+)(?:\((?P<children>.*)\))?$!", $string, $matches)) {
            throw new DriverException("Cannot convert '{$string}' to node.");
        } else {
            $node = $this->factory->createNodeFromLabel($this->unescapeValue($matches['value']));

            $children = !empty($matches['children']) ? trim($matches['children']) : '';
            foreach ($this->explode($children) as $childString)
            {
                $node->addChild($this->stringToTree($childString));
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