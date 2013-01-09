<?php

namespace Goutte\TreeBundle\Is;

/**
 * This is the base interface for a Node in a Rooted Tree
 * Look at Goutte\TreeBundle\Model\AbstractNode for an implementation of these
 */
interface Node {

    /**
     * Am I the root node of the tree ?
     *
     * @return bool
     */
    public function isRoot();

    /**
     * Am I a leaf in the tree ?
     * Note: the root may be a leaf if the node is alone
     *
     * @return bool
     */
    public function isLeaf();

    /**
     * Am I the direct child of the specified $node ?
     *
     * @param Node $node
     * @return bool
     */
    public function isChildOf(Node $node);

    /**
     * Am I a descendant of the specified $node ?
     *
     * @param Node $node
     * @return bool
     */
    public function isDescendantOf(Node $node);

    /**
     * Am I an ancestor of the specified $node ?
     *
     * @param Node $node
     * @return bool
     */
    public function isAncestorOf(Node $node);

    /**
     * Am I the direct parent of specified $node ?
     *
     * @param Node $node
     * @return bool
     */
    public function isParentOf(Node $node);

    /**
     * Get the root node of the tree
     *
     * @return Node
     */
    public function getRoot();

    /**
     * Get my previous sibling, or null
     *
     * @return Node|null
     */
    public function getPreviousSibling();

    /**
     * Get ny next sibling, or null
     *
     * @return Node|null
     */
    public function getNextSibling();

    /**
     * Get my parent, or null if I am the root
     *
     * @return Node|null
     */
    public function getParent();

    /**
     * Get the children nodes as an array
     *
     * @return Node[]
     */
    public function getChildren();

    /**
     * Same as ->getChildren()[$n]
     *
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
     * Get the last of the children, or null if I am a leaf
     *
     * @return Node|null
     */
    public function getLastChild();

    /**
     * Gets all the descendants
     *
     * @return array Will be empty if $this is a leaf
     */
    public function getDescendants();

    /**
     * Get a random descendant node, or null if there are no descendants.
     *
     * @param bool $includeSelf Optional. When true, the randomly chosen node may be $this
     * @return Node|null
     */
    public function getRandomDescendant($includeSelf=false);

    /**
     * Changes my parent to the specified node
     * Should move my whole subtree
     * Should make me root of my own tree if specified $node is null
     *
     * @throws \Goutte\TreeBundle\Exception\CyclicReferenceException when a simple cycle is detected
     * @param Node|null $node
     */
    public function setParent($node);

    /**
     * Adds specified node as child
     * Should move the whole subtree of the child
     *
     * @throws \Goutte\TreeBundle\Exception\CyclicReferenceException when a simple cycle is detected
     * @param Node $node
     */
    public function addChild(Node $node);

    /**
     * Removes the specified node from the children
     * Specified node will then be the root of its own tree
     *
     * @param Node $node
     */
    public function removeChild(Node $node);

    /**
     * Removes all children of this node
     */
    public function removeChildren();

    /**
     * Get an array of the nodes along the shortest path to the specified node,
     * excluding this node and the specified node
     *
     * @throws \Goutte\TreeBundle\Exception\DisjointNodesException when path does not exist
     * @param Node $node
     * @return Node[]
     */
    public function getNodesAlongThePathTo(Node $node);

    /**
     * Replace this node by the specified $node in the tree this node is in.
     * This node will be all alone afterwards.
     *
     * @param Node $node
     * @return mixed
     */
    public function replaceBy(Node $node);

    /**
     * The label should be castable as string
     *
     * @return mixed
     */
    public function getLabel();

    /**
     * @param $label
     */
    public function setLabel($label);

}