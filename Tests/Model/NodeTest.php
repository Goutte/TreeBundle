<?php

namespace Goutte\TreeBundle\Tests\Model;

use Goutte\TreeBundle\Model\Node;

class NodeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Node
     */
    protected $nodeA = null;
    /**
     * @var Node
     */
    protected $nodeB = null;
    /**
     * @var Node
     */
    protected $nodeC = null;
    /**
     * @var Node
     */
    protected $nodeD = null;
    /**
     * @var Node
     */
    protected $nodeE = null;

    public function setUp()
    {
        $this->nodeA = $this->getNode();
        $this->nodeB = $this->getNode();
        $this->nodeC = $this->getNode();
        $this->nodeD = $this->getNode();
        $this->nodeE = $this->getNode();
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

    /**
     * @return Node
     */
    protected function getNode()
    {
        return $this->getMockForAbstractClass('Goutte\TreeBundle\Model\Node');
    }
}