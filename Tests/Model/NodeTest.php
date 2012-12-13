<?php

namespace Goutte\TreeBundle\Tests\Model;

use Goutte\TreeBundle\Model\Node;

class NodeTest extends \PHPUnit_Framework_TestCase
{
    /** @var Node */
    protected $nodeA = null;
    /** @var Node */
    protected $nodeB = null;
    /** @var Node */
    protected $nodeC = null;
    /** @var Node */
    protected $nodeD = null;
    /** @var Node */
    protected $nodeE = null;
    /** @var Node */
    protected $nodeF = null;
    /** @var Node */
    protected $nodeG = null;

    public function setUp()
    {
        $this->nodeA = $this->getNode();
        $this->nodeB = $this->getNode();
        $this->nodeC = $this->getNode();
        $this->nodeD = $this->getNode();
        $this->nodeE = $this->getNode();
        $this->nodeF = $this->getNode();
        $this->nodeG = $this->getNode();
    }

    public function tearDown()
    {
        unset($this->nodeA,$this->nodeB,$this->nodeC,$this->nodeD,$this->nodeE,$this->nodeF,$this->nodeG);
    }

    public function testIsRoot()
    {
        $this->assertTrue($this->nodeA->isRoot(), "It should initially be the root");

        $this->nodeA->setParent($this->nodeB);
        $this->assertFalse($this->nodeA->isRoot(), "It should not be the root if it has a parent");

        $this->nodeA->setParent(null);
        $this->assertTrue($this->nodeA->isRoot(), "It should be the root again if its parent is set to null");
    }

    public function testIsLeaf()
    {
        $this->assertTrue($this->nodeA->isLeaf(), "It should initially be a leaf");

        $this->nodeA->addChild($this->nodeB);
        $this->assertFalse($this->nodeA->isLeaf(), "It should not be a leaf if it has a child");

        $this->nodeB->setParent($this->nodeC);
        $this->assertFalse($this->nodeC->isLeaf(), "It should not be a leaf if it's the parent of another node");
    }

    public function testIsChildOf()
    {
        $this->assertFalse($this->nodeA->isChildOf($this->nodeB), "It should not initially be the child of anybody");

        $this->nodeB->addChild($this->nodeA);
        $this->assertTrue($this->nodeA->isChildOf($this->nodeB), "It should be the child of the node it was added as child of");

        $this->nodeB->setParent($this->nodeC);
        $this->assertTrue($this->nodeB->isChildOf($this->nodeC), "It should be the child of the node set as parent");
    }

    // Using Test Tree /////////////////////////////////////////////////////////////////////////////////////////////////

    public function setUpTestTree()
    {
        // A
        // +--B
        // |  +--C
        // |  +--D
        // |  |  +--G
        // |  +--E
        // +--F

        $this->nodeA->addChild($this->nodeB);
        $this->nodeA->addChild($this->nodeF);
        $this->nodeB->addChild($this->nodeC);
        $this->nodeB->addChild($this->nodeD);
        $this->nodeB->addChild($this->nodeE);
        $this->nodeD->addChild($this->nodeG);
    }

    public function testIsParentOf()
    {
        $this->setUpTestTree();

        $this->assertFalse($this->nodeA->isParentOf($this->nodeA), "It should not be the parent of itself");

        $this->assertTrue($this->nodeA->isParentOf($this->nodeB));
        $this->assertTrue($this->nodeA->isParentOf($this->nodeF));
        $this->assertFalse($this->nodeA->isParentOf($this->nodeC));

        $this->assertTrue($this->nodeB->isParentOf($this->nodeC));
        $this->assertTrue($this->nodeB->isParentOf($this->nodeD));
        $this->assertFalse($this->nodeB->isParentOf($this->nodeG));

        $this->assertTrue($this->nodeD->isParentOf($this->nodeG));
        $this->assertFalse($this->nodeG->isParentOf($this->nodeA));
    }

    public function testGetPreviousSibling()
    {
        $this->setUpTestTree();

        $this->assertEquals($this->nodeB, $this->nodeF->getPreviousSibling(), "It should return the previous sibling");
        $this->assertNull($this->nodeB->getPreviousSibling(), "It should retourn null if there is no previous sibling");
    }

    public function testGetNextSibling()
    {
        $this->setUpTestTree();

        $this->assertEquals($this->nodeF, $this->nodeB->getNextSibling(), "It should return the next sibling");
        $this->assertNull($this->nodeF->getNextSibling(), "It should retourn null if there is no next sibling");
    }

    public function testGetParent()
    {
        $this->setUpTestTree();

        $this->assertEquals($this->nodeA, $this->nodeB->getParent(), "It should return the direct parent");
        $this->assertEquals($this->nodeD, $this->nodeG->getParent(), "It should return the direct parent");

        $this->assertNull($this->nodeA->getParent(), "It should retourn null if it is the root node");
    }

    public function testGetRoot()
    {
        $this->setUpTestTree();

        $this->assertEquals($this->nodeA, $this->nodeA->getRoot(), "It should return itself if it is the root");
        $this->assertEquals($this->nodeA, $this->nodeB->getRoot(), "It should return the root node");
        $this->assertEquals($this->nodeA, $this->nodeG->getRoot(), "It should return the root node");
    }

    public function testIsDescendantOf()
    {
        $this->setUpTestTree();

        foreach(array('B','C','D','E','F','G') as $nodeLetter) {
            /** @var $node Node */
            $node = $this->{'node'.$nodeLetter};
            $this->assertTrue($node->isDescendantOf($this->nodeA), "All nodes but the root should be the descendant of the root");
        }

        $this->assertTrue($this->nodeB->isDescendantOf($this->nodeA), "It should be the descendant of its direct parent");
        $this->assertTrue($this->nodeG->isDescendantOf($this->nodeB), "It should be the descendant of an ancestor");
        $this->assertFalse($this->nodeB->isDescendantOf($this->nodeB), "It should not be a descendant of itself");
        $this->assertFalse($this->nodeB->isDescendantOf($this->nodeC), "It should not be a descendant of a child");
        $this->assertFalse($this->nodeB->isDescendantOf($this->nodeF), "It should not be a descendant of a sibling");
    }

    public function testIsAncestorOf()
    {
        $this->setUpTestTree();

        foreach(array('B','C','D','E','F','G') as $nodeLetter) {
            /** @var $node Node */
            $node = $this->{'node'.$nodeLetter};
            $this->assertTrue($this->nodeA->isAncestorOf($node), "The root should be the ancestor of all nodes but itself");
        }

        $this->assertTrue($this->nodeB->isAncestorOf($this->nodeC), "It should be the ancestor of its direct children");
        $this->assertTrue($this->nodeB->isAncestorOf($this->nodeG), "It should be the ancestor of a descendant");
        $this->assertFalse($this->nodeB->isAncestorOf($this->nodeB), "It should not be an ancestor of itself");
        $this->assertFalse($this->nodeD->isAncestorOf($this->nodeB), "It should not be an ancestor of a parent");
        $this->assertFalse($this->nodeD->isAncestorOf($this->nodeE), "It should not be an ancestor of a sibling");
    }


    /**
     * @pending Implementation
     */
    public function testGetNodesAlongThePathTo()
    {
        $this->setUpTestTree();

        $expectedPath = array($this->nodeB, $this->nodeD);
        $computedPath = $this->nodeE->getNodesAlongThePathTo($this->nodeG);

        $this->assertEquals($expectedPath, $computedPath, "It should find the shortest path to the destination node");

        $this->assertEquals(array(), $this->nodeD->getNodesAlongThePathTo($this->nodeD), "It should return an empty array if the destination node is itself");
        $this->assertEquals(array(), $this->nodeD->getNodesAlongThePathTo($this->nodeG), "It should return an empty array if the destination node is adjacent (child)");
        $this->assertEquals(array(), $this->nodeF->getNodesAlongThePathTo($this->nodeA), "It should return an empty array if the destination node is adjacent (parent)");

        // It should throw a ~ForeignNodesException if the destination node is not on the same tree
    }


    /**
     * @return Node
     */
    protected function getNode()
    {
        return $this->getMockForAbstractClass('Goutte\TreeBundle\Model\Node');
    }
}

// A
// +--B
// |  +--C
// |  +--D
// |  |  +--G
// |  +--E
// +--F