<?php

namespace Goutte\TreeBundle\Tests\Driver;

class ParenthesisTest extends \PHPUnit_Framework_TestCase
{

    /** @var \Goutte\TreeBundle\Driver\Parenthesis */
    protected $parenthesis;

    public function setUp()
    {

        $this->parenthesis = $this->getMockBuilder('Goutte\TreeBundle\Driver\Parenthesis')
                                  ->setConstructorArgs(array(new \Goutte\TreeBundle\Tests\Model\NodeFactory()))
                                  ->getMockForAbstractClass();
    }

    /**
     * @dataProvider treeAsStringProvider
     * @param $string
     */
    public function testDriver($string)
    {
        $node = $this->parenthesis->stringToNode($string);
        $stringReturned = $this->parenthesis->nodeToString($node);

        $this->assertEquals($string, $stringReturned);
    }

    public function treeAsStringProvider()
    {
        return array(
            array("A(B(),C(),D())"),
            array("A(B(),C(D(E(F(G())))),H(),I(),J())"),
            array("*(+(6(),migite.thumb.tip.sphericalCoordinates.ro()),2())"),
        );
    }

}