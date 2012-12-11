<?php

namespace Goutte\TreeBundle\Tests\TestCase;

abstract class DriverTestCase extends \PHPUnit_Framework_TestCase implements DriverTestCaseInterface
{

    /** @var \Goutte\TreeBundle\Is\Driver */
    protected $driver;

    public function setUp()
    {
        $this->driver = $this->getMockBuilder($this->getDriverClass())
                             ->setConstructorArgs(array(new \Goutte\TreeBundle\Tests\Model\NodeFactory()))
                             ->getMockForAbstractClass();
    }

    /**
     * @dataProvider treeAsStringProvider
     * @param $initialString
     */
    public function testDriver($initialString)
    {
        $node = $this->driver->stringToNode($initialString);
        $resultString = $this->driver->nodeToString($node);

        $this->assertEquals($initialString, $resultString, "It should be the same string after a back-and-forth conversion");
    }

    public function treeAsStringProvider()
    {
        return array();
    }

}