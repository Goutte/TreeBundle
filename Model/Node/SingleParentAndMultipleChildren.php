<?php

namespace Goutte\TreeBundle\Model\Node;

use Goutte\TreeBundle\Is\Node;

use Goutte\TreeBundle\Exception\CyclicReferenceException;
use Goutte\TreeBundle\Exception\DisjointNodesException;
use Goutte\TreeBundle\Exception\TreeIntegrityException;

/**
 * This needs proper decoupling for the GraphTheoryBundle
 */
trait SingleParentAndMultipleChildren
{
    /**
     * The parent Node, or null if this is the root
     * @var Node
     */
    protected $parent = null;

    /**
     * An array of Nodes that are the direct children of this Node
     * @var Node[]
     */
    protected $children = array();


    function __construct()
    {
        $this->children = array();
    }

    /**
     * Cloning will truncate the tree at the cloned node, making it the root.
     * It will clone the children, though.
     */
    function __clone()
    {
        foreach ($this->children as $kChild => $child) {
            $this->children[$kChild] = clone $this->children[$kChild];
            $this->children[$kChild]->setParent($this);
        }
    }


    public function getParent()
    {
        return $this->parent;
    }

    public function setParent($node, $careAboutIntegrity=true)
    {
        if (null !== $node && ($this === $node || $this->isAncestorOf($node))) {
            throw new CyclicReferenceException();
        }

        if ($careAboutIntegrity && !$this->isRoot()) {
            $this->getParent()->removeChild($this);
        }

        $this->parent = $node;
        if ($node && !$node->isParentOf($this)) {
            $node->addChild($this);
        }
    }


    public function isRoot()
    {
        return (null === $this->parent);
    }

    public function isLeaf()
    {
        return (0 === count($this->children));
    }

    public function isChildOf(Node $node)
    {
        return ($node === $this->parent);
    }

    public function isParentOf(Node $node)
    {
        return in_array($node, $this->children, true); // strict, or will l∞p
    }

    public function isDescendantOf(Node $node)
    {
        if ($this->isChildOf($node)) {
            return true;
        } else {
            if ($this->isRoot()) {
                return false;
            } else {
                return $this->getParent()->isDescendantOf($node);
            }
        }
    }

    public function isAncestorOf(Node $node)
    {
        return $node->isDescendantOf($this);
    }

    public function getRoot()
    {
        if ($this->isRoot()) {
            return $this;
        } else {
            return $this->getParent()->getRoot();
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


    public function getChildren()
    {
        return $this->children;
    }

    public function getNthChild($n)
    {
        return !empty($this->children[$n-1]) ? $this->children[$n-1] : null;
    }

    public function getFirstChild()
    {
        return $this->getNthChild(1);
    }

    public function getSecondChild()
    {
        return $this->getNthChild(2);
    }

    public function getThirdChild()
    {
        return $this->getNthChild(3);
    }

    public function getFourthChild()
    {
        return $this->getNthChild(4);
    }

    public function getFifthChild()
    {
        return $this->getNthChild(5);
    }

    public function getLastChild()
    {
        if ($this->isLeaf()) {
            return null;
        } else {
            return $this->children[count($this->children)-1];
        }
    }

    public function getDescendants($includeSelf=false)
    {
        // todo: allow for customization of tree flattening
        $descendants = $this->getChildren();
        foreach ($this->getChildren() as $child) {
            $descendants = array_merge($descendants, $child->getDescendants());
        }

        if ($includeSelf) {
            $descendants = array_merge(array($this), $descendants);
        }

        return $descendants;
    }

    public function addChild(Node $node)
    {
        if ($this === $node || $this->isDescendantOf($node)) {
            throw new CyclicReferenceException();
        }

        if (!$this->isParentOf($node)) {
            if (!$node->isRoot()) {
                $node->getParent()->removeChild($node);
            }
            $this->children[] = $node; // first, or will l∞p
            $node->setParent($this, false);
        }
    }

    public function removeChild(Node $node)
    {
        if ($this->isParentOf($node)) {
            unset($this->children[array_search($node, $this->children, true)]);
            $this->children = array_values($this->children);
            $node->setParent(null, false);
        }
    }


    public function removeChildren()
    {
        $children = $this->getChildren();
        foreach ($children as $child) {
            $this->removeChild($child);
        }
    }


    /**
     * May be insanely optimized performance-wise, i trust
     * But i kinda like the simplicity of this
     */
    public function getNodesAlongThePathTo(Node $node)
    {
        if ($this->isParentOf($node) || $this->isChildOf($node) || $this === $node) { // node is adjacent or self
            return array();
        }

        if ($this->isAncestorOf($node)) {
            // join the path to the node's parent and the node's parent
            return array_merge($this->getNodesAlongThePathTo($node->getParent()), array($node->getParent()));
        } else {
            if ($this->isRoot()) {
                // i am root but not your ancestor and you're not me, we are not on the same tree then
                throw new DisjointNodesException("Cannot build path between disjoint nodes.");
            } else {
                // join my parent and the path from my parent to the node
                return array_merge(array($this->getParent()), $this->getParent()->getNodesAlongThePathTo($node));
            }
        }
    }


    public function replaceBy(Node $node)
    {
        $parent = $this->getParent();
        $children = $this->getChildren();

        foreach ($children as $child)
        {
            $child->setParent($node);
        }

        if ($parent) {
            $siblings = array();
            foreach ($parent->getChildren() as $sibling)
            {
                if ($sibling === $this) {
                    $siblings[] = $node;
                } else {
                    $siblings[] = $sibling;
                }

                $parent->removeChild($sibling);
            }

            foreach ($siblings as $sibling)
            {
                $parent->addChild($sibling);
            }
        }
    }
}