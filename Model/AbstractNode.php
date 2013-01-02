<?php

namespace Goutte\TreeBundle\Model;

use Goutte\TreeBundle\Is\Node as NodeInterface;
use Goutte\TreeBundle\Util\Random;
use Goutte\TreeBundle\Is\Random as RandomInterface;
use Goutte\TreeBundle\Exception\CyclicReferenceException;
use Goutte\TreeBundle\Exception\DisjointNodesException;
use Goutte\TreeBundle\Exception\TreeIntegrityException;

/**
 * Look at Goutte\TreeBundle\Tests\Model\TestNode for a description of each method implementation
 * Look at NodeInterface for comments
 */
abstract class AbstractNode implements NodeInterface
{
    /**
     * The parent Node, or null if this is the root
     * @var NodeInterface
     */
    protected $parent = null;

    /**
     * The value held by the Node, may be pretty much anything (operator function, operand, etc.)
     * but must be "stringable" for some Drivers
     * @var mixed
     */
    protected $label = '';

    /**
     * An array of Nodes that are the direct children of this Node
     * @var NodeInterface[]
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

    function __toString()
    {
        return $this->getLabel();
    }


    public function isRoot()
    {
        return (null === $this->parent);
    }

    public function isLeaf()
    {
        return (0 === count($this->children));
    }

    public function isChildOf(NodeInterface $node)
    {
        return ($node === $this->parent);
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
            if ($this->isRoot()) {
                return false;
            } else {
                return $this->getParent()->isDescendantOf($node);
            }
        }
    }

    public function isAncestorOf(NodeInterface $node)
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

    public function getParent()
    {
        return $this->parent;
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

    public function getDescendants()
    {
        // todo: allow for customization of tree flattening
        $descendants = $this->getChildren();
        foreach ($this->getChildren() as $child) {
            $descendants = array_merge($descendants, $child->getDescendants());
        }

        return $descendants;
    }

    public function getRandomDescendant($includeSelf=false, $random=null)
    {
        $pool = $this->getDescendants();
        if ($includeSelf) {
            $pool = array_merge(array($this), $pool);
        }

        if (empty($pool)) {
            return null;
        } else {
            if (empty($random)) $random = new Random();
            if ($random instanceof RandomInterface) {
                return $random->pickArrayValue($pool);
            } else {
                trigger_error("The \$random parameter must be an instance of Goutte\\TreeBundle\\Is\\Random.", E_USER_ERROR);
                return null;
            }
        }
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


    public function addChild(NodeInterface $node)
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

    public function removeChild(NodeInterface $node) {
        if ($this->isParentOf($node)) {
            unset($this->children[array_search($node, $this->children, true)]);
            $this->children = array_values($this->children);
            $node->setParent(null, false);
        }
    }

    /**
     * May be insanely optimized performance-wise, i trust
     * But i kinda like the simplicity of this
     */
    public function getNodesAlongThePathTo(NodeInterface $node)
    {
        if ($this === $node || $this->isParentOf($node) || $this->isChildOf($node)) {
            return array();
        }

        if ($this->isAncestorOf($node)) {
            return array_merge($this->getNodesAlongThePathTo($node->getParent()), array($node->getParent()));
        } else {
            if ($this->isRoot()) {
                // i am root but not your ancestor and you're not me, we are not on the same tree then
                throw new DisjointNodesException("Cannot build path between disjoint nodes.");
            } else {
                return array_merge(array($this->getParent()), $this->getParent()->getNodesAlongThePathTo($node));
            }
        }
    }


    public function replaceBy(NodeInterface $node)
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


    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel($label)
    {
        $this->label = $label;
    }

}