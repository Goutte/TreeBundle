<?php

namespace Goutte\TreeBundle\Model\Tree;

use Goutte\TreeBundle\Exception\EmptyTreeException;
use Goutte\TreeBundle\Is\Node;

trait ComposeWithRootNode
{
    protected $_root;

    public function __construct(Node $root=null)
    {
        if (!empty($root)) $this->setRoot($root);
    }

    /**
     * @throws EmptyTreeException
     * @return Node|null
     */
    public function getRoot()
    {
        if (empty($this->_root)) throw new EmptyTreeException;

        return $this->_root;
    }

    public function setRoot(Node $root)
    {
        return $this->_root;
    }

    public function isRoot()
    {
        return true;
    }

    public function getPreviousSibling()
    {
        return null;
        //return $this->getRoot()->getPreviousSibling();
    }

    public function getNextSibling()
    {
        return null;
        //return $this->getRoot()->getNextSibling();
    }

    public function getParent()
    {
        return null;
        //return $this->getRoot()->getParent();
    }

    public function isLeaf()
    {
        return $this->getRoot()->isLeaf();
    }

    public function isChildOf(Node $node)
    {
        return $this->getRoot()->isChildOf($node);
    }

    public function isDescendantOf(Node $node)
    {
        return $this->getRoot()->isDescendantOf($node);
    }

    public function isAncestorOf(Node $node)
    {
        return $this->getRoot()->isAncestorOf($node);
    }

    public function isParentOf(Node $node)
    {
        return $this->getRoot()->isParentOf($node);
    }

    public function getChildren()
    {
        return $this->getRoot()->getChildren();
    }

    public function getNthChild($n)
    {
        return $this->getRoot()->getNthChild($n);
    }

    public function getFirstChild()
    {
        return $this->getRoot()->getFirstChild();
    }

    public function getSecondChild()
    {
        return $this->getRoot()->getSecondChild();
    }

    public function getThirdChild()
    {
        return $this->getRoot()->getThirdChild();
    }

    public function getFourthChild()
    {
        return $this->getRoot()->getFourthChild();
    }

    public function getFifthChild()
    {
        return $this->getRoot()->getFifthChild();
    }

    public function getLastChild()
    {
        return $this->getRoot()->getLastChild();
    }

    public function getDescendants()
    {
        return $this->getRoot()->getDescendants();
    }

    public function getRandomDescendant($includeSelf=false)
    {
        return $this->getRoot()->getRandomDescendant($includeSelf);
    }

    public function setParent($node)
    {
        $this->getRoot()->setParent($node);
    }

    public function addChild(Node $node)
    {
        $this->getRoot()->addChild($node);
    }

    public function removeChild(Node $node)
    {
        $this->getRoot()->removeChild($node);
    }

    public function removeChildren()
    {
        $this->getRoot()->removeChildren();
    }

    public function getNodesAlongThePathTo(Node $node)
    {
        return $this->getRoot()->getNodesAlongThePathTo($node);
    }

    public function replaceBy(Node $node)
    {
        return $this->getRoot()->replaceBy($node);
    }

    public function getLabel()
    {
        return $this->getRoot()->getLabel();
    }

    public function setLabel($label)
    {
        $this->getRoot()->setLabel($label);
    }
}