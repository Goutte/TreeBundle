<?php

namespace Goutte\TreeBundle\Is;

/**
 * Look at Goutte\TreeBundle\Model\AbstractNode for an implementation of these
 */
interface Node {

    /**
     * Am I the topmost/root node of the tree ?
     * @return bool
     */
    public function isRoot();

    /**
     * Am I a leaf in the tree ?
     * @return bool
     */
    public function isLeaf();

    /**
     * Am I the direct child of the specified $node ?
     * @param Node $node
     * @return bool
     */
    public function isChildOf(Node $node);

    /**
     * Am I a descendant of the specified $node ?
     * @param Node $node
     * @return bool
     */
    public function isDescendantOf(Node $node);

    /**
     * Am I an ancestor of the specified $node ?
     * @param Node $node
     * @return bool
     */
    public function isAncestorOf(Node $node);

    /**
     * Am I the direct parent of specified $node ?
     * @param Node $node
     * @return bool
     */
    public function isParentOf(Node $node);

    /**
     * @return Node
     */
    public function getRoot();

    /**
     * @return Node|null
     */
    public function getPreviousSibling();

    /**
     * @return Node|null
     */
    public function getNextSibling();

    /**
     * @return Node|null
     */
    public function getParent();

    /**
     * @param Node|null $node
     */
    public function setParent($node);

    /**
     * @return Node[]
     */
    public function getChildren();

    /**
     * @param $n
     * @return Node|null
     */
    public function getNthChild($n);

    /**
     * @return Node|null
     */
    public function getFirstChild();

    /**
     * @return Node|null
     */
    public function getSecondChild();

    /**
     * @return Node|null
     */
    public function getThirdChild();

    /**
     * @return Node|null
     */
    public function getFourthChild();

    /**
     * @return Node|null
     */
    public function getFifthChild();

    /**
     * @return Node|null
     */
    public function getLastChild();

    /**
     * @param Node $node
     */
    public function addChild(Node $node);

    /**
     * @param Node $node
     */
    public function removeChild(Node $node);

    /**
     * @param Node $node
     * @return array
     */
    public function getNodesAlongThePathTo(Node $node);

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @param $value
     */
    public function setValue($value);

}