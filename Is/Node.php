<?php

namespace Goutte\TreeBundle\Is;

interface Node {

    /**
     * @return bool
     */
    public function isRoot();

    /**
     * @return bool
     */
    public function isLeaf();

    /**
     * @param Node $node
     * @return bool
     */
    public function isChildOf(Node $node);

    /**
     * @param Node $node
     * @return bool
     */
    public function isDescendantOf(Node $node);

    /**
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
     * @return Node
     */
    public function getRoot();

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @param $value
     */
    public function setValue($value);

}