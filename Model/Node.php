<?php

namespace Goutte\TreeBundle\Model;

use Goutte\TreeBundle\Is\Node as NodeInterface;
use Goutte\TreeBundle\Exception\TreeIntegrityException;

abstract class Node implements NodeInterface
{
    /**
     * The parent Node, or null if this is the root
     * @var NodeInterface
     */
    protected $parent;

    /**
     * The value held by the Node, may be pretty much anything (operator function, operand, etc.) but must be
     * "stringable" for some Drivers
     * @var mixed
     */
    protected $value;

    /**
     * An array of Nodes that are the direct children of this Node
     * @var NodeInterface[]
     */
    protected $children;


    function __construct()
    {
        $this->parent = null;
        $this->children = array();
    }


    public function isRoot()
    {
        return (null == $this->parent);
    }

    public function isLeaf()
    {
        return (0 === count($this->children));
    }

    public function isChildOf(NodeInterface $node)
    {
        return ($node == $this->parent);
    }

    public function isParentOf(NodeInterface $node)
    {
        return in_array($node, $this->children, true); // strict, or will l∞p
    }

    public function isDescendantOf(NodeInterface $node)
    {
        if ($this->isChildOf($node)) {
            return true;
        } else {
            if (!$this->isRoot()) {
                return $this->getParent()->isDescendantOf($node);
            } else {
                return false;
            }
        }
    }

    public function getPreviousSibling()
    {
        if ($this->parent) {
            $siblings = $this->parent->getChildren();
            $index = array_search($this, $siblings);

            if (false === $index) throw new TreeIntegrityException();

            if (0 < $index) {
                return $siblings[$index - 1];
            }
        }

        return null;
    }

    public function getNextSibling()
    {
        if ($this->parent) {
            $siblings = $this->parent->getChildren();
            $index = array_search($this, $siblings);

            if (false === $index) throw new TreeIntegrityException();

            if ($index < (count($siblings) - 1)) {
                return $siblings[$index + 1];
            }
        }

        return null;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setParent($node)
    {
        $this->parent = $node;
        if ($node && !$node->isParentOf($this)) {
            $node->addChild($this);
        }
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function addChild(NodeInterface $node)
    {
        if (!$this->isParentOf($node)) {
            $this->children[] = $node; // first, or will l∞p
            $node->setParent($this);
        }
    }

    public function getRoot()
    {
        if ($this->isRoot()) {
            return $this;
        } else {
            return $this->getParent()->getRoot();
        }
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

}