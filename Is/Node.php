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
     * @param Node $node
     */
    public function setParent(Node $node);

    /**
     * @return Node[]
     */
    public function getChildren();

    /**
     * @return Tree|null
     */
    public function getTree();

    /**
     * @param Tree $tree
     */
    public function setTree(Tree $tree);

}