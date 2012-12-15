<?php

namespace Goutte\TreeBundle\Tests\TestCase;

use Goutte\TreeBundle\Model\AbstractNode;

abstract class DriverTestCase extends \PHPUnit_Framework_TestCase implements DriverTestCaseInterface
{
    /** @var \Goutte\TreeBundle\Is\Driver */
    protected $driver;

    public function setUp()
    {
        $this->driver = $this->getMockBuilder($this->getDriverClass())
                             ->setConstructorArgs(array('Goutte\TreeBundle\Model\Node'))
                             ->getMockForAbstractClass();
    }

    /**
     * Expects the provided tree structure as string to convert from $initialString to $expectedString
     * after a back and forth conversion to Node(s)
     *
     * @dataProvider treeAsStringThatConvertsInto
     * @param $initialString
     * @param $expectedString
     */
    public function testBackAndForthConversionConvertsInto($initialString, $expectedString)
    {
        $node = $this->driver->stringToNode($initialString);
        $resultString = $this->driver->nodeToString($node);

        $this->assertEquals($expectedString, $resultString, "It should convert to the expected tree string after a back-and-forth conversion");
    }

    /**
     * Expects the provided tree structure as string to stay the same after a back and forth conversion to Node(s)
     *
     * @dataProvider treeAsStringThatStaysTheSameProvider
     * @param $initialString
     */
    public function testBackAndForthConversionStaysTheSame($initialString)
    {
        $node = $this->driver->stringToNode($initialString);
        $resultString = $this->driver->nodeToString($node);

        $this->assertEquals($initialString, $resultString, "It should be the same tree string after a back-and-forth conversion");
    }

    /**
     * @return AbstractNode
     */
    protected function createNode($value=null)
    {
        $mock = $this->getMockForAbstractClass('Goutte\TreeBundle\Model\AbstractNode');
        if (null !== $value) $mock->setValue($value);

        return $mock;
    }
}