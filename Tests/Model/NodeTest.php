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

    public function setUpTestTree()
    {
        // A
        // +--B
        // |  +--C
        // |  +--D
        // |  |  +--G
        // |  |
        // |  +--E
        // |
        // +--F

        $this->nodeA->addChild($this->nodeB);
        $this->nodeA->addChild($this->nodeF);
        $this->nodeB->addChild($this->nodeC);
        $this->nodeB->addChild($this->nodeD);
        $this->nodeB->addChild($this->nodeE);
        $this->nodeD->addChild($this->nodeG);
    }

    public function testIsRoot()
    {
        $this->assertTrue($this->nodeA->isRoot(), "It should be the root if it's alone");

        $this->nodeA->setParent($this->nodeB);
        $this->assertFalse($this->nodeA->isRoot(), "It should not be the root if it has a parent");

        $this->nodeA->setParent(null);
        $this->assertTrue($this->nodeA->isRoot(), "It should be the root again if its parent is set to null");
    }

    public function testIsLeaf()
    {
        $this->assertTrue($this->nodeA->isLeaf(), "It should be a leaf if it's alone");

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

    public function testHasChild()
    {
        $this->setUpTestTree();

        $this->assertTrue($this->nodeA->hasChild($this->nodeB));
        $this->assertTrue($this->nodeA->hasChild($this->nodeF));
        $this->assertFalse($this->nodeA->hasChild($this->nodeC));

        $this->assertTrue($this->nodeB->hasChild($this->nodeC));
        $this->assertTrue($this->nodeB->hasChild($this->nodeD));
        $this->assertFalse($this->nodeB->hasChild($this->nodeG));

        $this->assertTrue($this->nodeD->hasChild($this->nodeG));
        $this->assertFalse($this->nodeG->hasChild($this->nodeA));
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

        $this->assertNull($this->nodeA->getParent(), "It should retourn null for the root node");
    }

    /**
     * @return Node
     */
    protected function getNode()
    {
        return $this->getMockForAbstractClass('Goutte\TreeBundle\Model\Node');
    }
}