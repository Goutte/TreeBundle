<?php

namespace Goutte\TreeBundle\Model;

use Goutte\TreeBundle\Is\Node as NodeInterface;
//use Goutte\TreeBundle\Is\Tree as TreeInterface;

abstract class Node implements NodeInterface
{
    /**
     * @var NodeInterface
     */
    protected $parent;

//    /**
//     * @var TreeInterface
//     */
//    protected $tree;


    /**
     * @var NodeInterface[]
     */
    protected $children;


    function __construct()
    {
        $this->parent = null;
        $this->children = array();
    }

    /**
     * @return bool
     */
    public function isRoot()
    {
        return (null == $this->parent);
    }

    /**
     * @return bool
     */
    public function isLeaf()
    {
        return (0 === count($this->children));
    }

    /**
     * @throws \Exception
     * @return NodeInterface|null
     */
    public function getPreviousSibling()
    {
        if ($this->parent) {
            $siblings = $this->parent->getChildren();
            $index = array_search($this, $siblings);

            if (false === $index) throw new \Exception("Parent / Child inconsistency.");

            if (0 < $index) {
                return $siblings[$index - 1];
            }
        }

        return null;
    }

    /**
     * @throws \Exception
     * @return NodeInterface|null
     */
    public function getNextSibling()
    {
        if ($this->parent) {
            $siblings = $this->parent->getChildren();
            $index = array_search($this, $siblings);

            if (false === $index) throw new \Exception("Parent / Child inconsistency.");

            if ($index < (count($siblings) - 1)) {
                return $siblings[$index + 1];
            }
        }

        return null;
    }

    /**
     * @return NodeInterface|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param NodeInterface|null $node
     */
    public function setParent($node)
    {
        $this->parent = $node;
        if ($node && !$node->hasChild($this)) {
            $node->addChild($this);
        }
    }

    /**
     * @return NodeInterface[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param NodeInterface $node
     */
    public function addChild(NodeInterface $node)
    {
        if (!$this->hasChild($node)) {
            $this->children[] = $node; // first, or will l∞p
            $node->setParent($this);
        }
    }

    /**
     * @param NodeInterface $node
     * @return bool
     */
    public function isChildOf(NodeInterface $node)
    {
        return ($node == $this->parent);
    }

    /**
     * @param NodeInterface $node
     * @return bool
     */
    public function isParentOf(NodeInterface $node)
    {
        return $this->hasChild($node);
    }

    /**
     * @param NodeInterface $node
     * @return bool
     */
    public function hasChild(NodeInterface $node)
    {
        return in_array($node, $this->children);
    }

    /**
     * @return NodeInterface
     */
    public function getRoot()
    {
        if ($this->isRoot()) {
            return $this;
        } else {
            return $this->getParent()->getRoot();
        }
    }


    // todo : think hard : do we really need the Tree class ?

//    /**
//     * @return TreeInterface|null
//     */
//    public function getTree()
//    {
//        return $this->tree;
//    }
//
//    /**
//     * @param TreeInterface $tree
//     */
//    public function setTree(TreeInterface $tree)
//    {
//        $this->tree = $tree;
//        foreach ($this->children as $childNode) {
//            $childNode->setTree($tree);
//        }
//    }
}