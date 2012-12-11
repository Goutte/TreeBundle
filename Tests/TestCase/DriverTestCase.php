<?php

namespace Goutte\TreeBundle\Tests\TestCase;

abstract class DriverTestCase extends \PHPUnit_Framework_TestCase implements DriverTestCaseInterface
{
    /** @var \Goutte\TreeBundle\Is\Driver */
    protected $driver;

    public function setUp()
    {
        $this->driver = $this->getMockBuilder($this->getDriverClass())
                             ->setConstructorArgs(array(new \Goutte\TreeBundle\Tests\Model\NodeFactory())) // how to mock here ? factory needs to create nodes !?!
                             ->getMockForAbstractClass();
    }

    /**
     * Expects the provided tree structure as string to stay the same after a back and forth conversion to Node(s)
     *
     * @dataProvider treeAsStringThatStaysTheSameProvider
     * @param $initialString
     */
    public function testBackAndForthConversion($initialString)
    {
        $node = $this->driver->stringToNode($initialString);
        $resultString = $this->driver->nodeToString($node);

        $this->assertEquals($initialString, $resultString, "It should be the same tree string after a back-and-forth conversion");
    }
}