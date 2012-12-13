<?php

namespace Goutte\TreeBundle\Is;

/**
 * Look at Goutte\TreeBundle\Tests\Model\TestNode for a description of each method implementation
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
     * @param Node $node
     */
    public function addChild(Node $node);

    /**
     * @param Node $node
     */
    public function removeChild(Node $node);

    /**
     * @return Node
     */
    public function getRoot();

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