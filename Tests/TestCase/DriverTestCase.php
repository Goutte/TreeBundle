<?php

namespace Goutte\TreeBundle\Tests\TestCase;

use Goutte\TreeBundle\Is\Node;
use Goutte\TreeBundle\Factory\NodeFactoryInterface;
use Goutte\TreeBundle\Tests\TestCase\DefaultNodeFactory;

abstract class DriverTestCase
    extends \PHPUnit_Framework_TestCase
    implements DriverTestCaseInterface, NodeFactoryInterface
{
    use DefaultNodeFactory;

    /** @var \Goutte\TreeBundle\Is\Driver */
    protected $driver;

    public function setUp()
    {
        // todo : try to understand what really happens here, and why getMockForAbstractClass works with non-abstract !?
        $factory = $this->getMockBuilder('Goutte\TreeBundle\Factory\DefaultNodeFactory')
                        ->setConstructorArgs(array('Goutte\TreeBundle\Model\Node'))
                        ->getMockForAbstractClass();
        $this->driver = $this->getMockBuilder($this->getDriverClass())
                             ->setConstructorArgs(array($factory))
                             ->getMockForAbstractClass();
    }

    /**
     * Expects the provided tree structure as string to convert from $initialString to $expectedString
     * after a back and forth conversion to Node(s)
     *
     * @dataProvider treeAsStringThatConvertsIntoProvider
     * @param $initialString
     * @param $expectedString
     */
    public function testBackAndForthConversionConvertsInto($initialString, $expectedString)
    {
        $node = $this->driver->stringToTree($initialString);
        $resultString = $this->driver->treeToString($node);

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
        $node = $this->driver->stringToTree($initialString);
        $resultString = $this->driver->treeToString($node);

        $this->assertEquals($initialString, $resultString, "It should be the same tree string after a back-and-forth conversion");
    }

    /**
     * @param string $label (optional) The value the node will hold
     * @return Node
     */
    protected function createNode($label='')
    {
        return $this->createNodeFromLabel($label);
    }
}