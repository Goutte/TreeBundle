<?php

namespace Goutte\TreeBundle\Tests\Model;

use Goutte\TreeBundle\Is\Node as NodeInterface;
use Goutte\TreeBundle\Is\Tree as TreeInterface;

use Goutte\TreeBundle\Model\Node;
use Goutte\TreeBundle\Model\Tree;

class CompositeTree implements TreeInterface
{
    use \Goutte\TreeBundle\Model\Tree\ComposeWithRootNode;
}

class CompositeTreeTest extends \PHPUnit_Framework_TestCase
{
    public function setUp(){}

    public function tearDown(){}

    public function testComposeWithRootNode()
    {
        $root = new Node();
        $root->setLabel('A');

        $tree = new CompositeTree($root);

        $this->assertEquals(true, $tree->isRoot(), "It should always be root");
        $this->assertNull($tree->getParent(), "It should not have a parent");
        $this->assertNull($tree->getPreviousSibling(), "It should not have siblings");
        $this->assertNull($tree->getNextSibling(), "It should not have siblings");

        $this->assertNull($tree->getRoot()->getParent(), "Its root node should not have a parent");

        // it should delegate other method calls to its root node
        // ~> build an array of compositing unparameterized method names as string,
        //    loop over it, assert equity of tree and root node results ? not really dry.
    }

    public function testExceptionOnEmptyTree()
    {
        $methodThatShouldThrow = 'getRoot';

        $tree = new CompositeTree(); // no root node

        try {
            $tree->{$methodThatShouldThrow}();
        } catch (\Goutte\TreeBundle\Exception\EmptyTreeException $e) {
            return;
        }

        $this->fail("It should throw EmptyTreeException whan calling '{$methodThatShouldThrow}' on an empty tree.");
    }

}

