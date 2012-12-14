<?php

namespace Goutte\TreeBundle\Tests\Model;

use Goutte\TreeBundle\Model\AbstractNode;
use Goutte\TreeBundle\Exception\TreeIntegrityException;
use Goutte\TreeBundle\Is\Node as NodeInterface;

class NodeTest extends \PHPUnit_Framework_TestCase
{
    /** @var NodeInterface */
    protected $nodeA = null;
    /** @var NodeInterface */
    protected $nodeB = null;
    /** @var NodeInterface */
    protected $nodeC = null;
    /** @var NodeInterface */
    protected $nodeD = null;
    /** @var NodeInterface */
    protected $nodeE = null;
    /** @var NodeInterface */
    protected $nodeF = null;
    /** @var NodeInterface */
    protected $nodeG = null;

    public function setUp()
    {
        $this->nodeA = $this->createNode();
        $this->nodeB = $this->createNode();
        $this->nodeC = $this->createNode();
        $this->nodeD = $this->createNode();
        $this->nodeE = $this->createNode();
        $this->nodeF = $this->createNode();
        $this->nodeG = $this->createNode();
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
            /** @var $node AbstractNode */
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
            /** @var $node AbstractNode */
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

        // It should throw a DisjointNodesException if the destination node is not on the same tree
        $this->setExpectedException('Goutte\\TreeBundle\\Exception\\DisjointNodesException');
        $this->nodeA->getNodesAlongThePathTo($this->createNode());
    }

    /**
     * We try to create a cycle by setting a descendant as parent
     * @expectedException \Goutte\TreeBundle\Exception\CyclicReferenceException
     */
    public function testExceptionWhenSettingDescendantAsParent()
    {
        $this->setUpTestTree();
        $this->nodeB->setParent($this->nodeD);
    }

    /**
     * We try to create a cycle by adding an ancestor as child
     * @expectedException \Goutte\TreeBundle\Exception\CyclicReferenceException
     */
    public function testExceptionWhenAddingAncestorAsChild()
    {
        $this->setUpTestTree();
        $this->nodeG->addChild($this->nodeB);
    }

    /**
     * Testing the hierarchy mutators, they must not break the tree's integrity
     * This is just to make sure the `children` and `parent` attributes stay consistent throughout the tree
     */
    public function testMovingNodesAroundAndKeepingIntegrity()
    {
        $this->setUpTestTree();

        $this->nodeB->addChild($this->nodeG);
        $this->nodeB->setParent($this->nodeF);
        $this->nodeA->addChild($this->nodeE);
        $this->nodeG->setParent($this->nodeE);

        $this->assertSubTreeIntegrity($this->nodeA);
    }


    /**
     * Asserts that the subtree rooted by the specified $node has integrity,
     * meaning that any node's children have said node as parent
     * @param NodeInterface $node
     */
    protected function assertSubTreeIntegrity(NodeInterface $node)
    {
        if (!$node->isLeaf()) {
            foreach ($node->getChildren() as $childNode) {
                if ($node !== $childNode->getParent()) {
                    $this->fail("The integrity of the tree is violated.");
                }
                $this->assertSubTreeIntegrity($childNode);
            }
        }
    }


    /**
     * @return AbstractNode
     */
    protected function createNode()
    {
        return $this->getMockForAbstractClass('Goutte\TreeBundle\Model\AbstractNode');
    }
}

// A
// +--B
// |  +--C
// |  +--D
// |  |  +--G
// |  +--E
// +--F