<?php

namespace Goutte\TreeBundle\Tests\Model;

use Goutte\TreeBundle\Model\Node;

class NodeTest extends \PHPUnit_Framework_TestCase
{
    public function testIsRoot()
    {
        $node01 = $this->getNode();
        $node02 = $this->getNode();

        $this->assertTrue($node01->isRoot(), "It should be the root if it's alone");

        $node01->setParent($node02);
        $this->assertFalse($node01->isRoot(), "It should not be the root if it has a parent");

        $node01->setParent(null);
        $this->assertTrue($node01->isRoot(), "It should be the root again if its parent is set to null");
    }

    public function testIsLeaf()
    {
        $node01 = $this->getNode();
        $node02 = $this->getNode();
        $node03 = $this->getNode();

        $this->assertTrue($node01->isLeaf(), "It should be a leaf if it's alone");

        $node01->addChild($node02);
        $this->assertFalse($node01->isLeaf(), "It should not be a leaf if it has a child");

        $node02->setParent($node03);
        $this->assertFalse($node03->isLeaf(), "It should not be a leaf if it's the parent of another node");
    }

    /**
     * @return Node
     */
    protected function getNode()
    {
        return $this->getMockForAbstractClass('Goutte\TreeBundle\Model\Node');
    }
}