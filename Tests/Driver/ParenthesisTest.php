<?php

namespace Goutte\TreeBundle\Tests\Driver;

use Goutte\TreeBundle\Tests\TestCase\DriverTestCase;

class ParenthesisTest extends DriverTestCase
{

    public function getDriverClass()
    {
        return 'Goutte\TreeBundle\Driver\Parenthesis';
    }

    public function testEscapingParenthesisAndCommas()
    {
        $nodeA = $this->createNode('A(A');
        $nodeB = $this->createNode('B,');
        $nodeC = $this->createNode('C');

        $nodeA->addChild($nodeB);
        $nodeA->addChild($nodeC);

        $expected = 'A\(A(B\,,C)';
        $actual = $this->driver->treeToString($nodeA);

        $this->assertEquals($expected, $actual, "It should escape parenthesis and commas in the labels");
    }

    public function testUnescapingParenthesisAndCommas()
    {
        $treeString = 'A\(A(B\,,C)';
        $nodeA = $this->driver->stringToTree($treeString);
        $children = $nodeA->getChildren();
        $nodeB = $children[0];
        $nodeC = $children[1];

        $this->assertEquals('A(A', $nodeA->getLabel(), "It should unescape parenthesis in the labels");
        $this->assertEquals('B,',  $nodeB->getLabel(), "It should unescape commas in the labels");
        $this->assertEquals('C',   $nodeC->getLabel(), "It should unescape parenthesis and commas in the labels");
    }

    public function treeAsStringThatConvertsIntoProvider()
    {
        return array(
            array("Alone()", "Alone"), // it should remove unnecessary parenthesis
            array("A(B(),C(),D(E))", "A(B,C,D(E))"), // it should remove unnecessary parenthesis
            array("A A( B(), C( ), D(E ))", "A A(B,C,D(E))"), // it should trim unnecessary spaces
            array('A\(A\,A(B\()', 'A\(A\,A(B\()'), // it should allow escaped parenthesis and commas
        );
    }

    public function treeAsStringThatStaysTheSameProvider()
    {
        return array(
            array("Alone"),
            array("A(B,C,D)"),
            array("A(B,C(D(E(F(G)))),H,I,J)"),
        );
    }

}