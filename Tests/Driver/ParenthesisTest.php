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
        $actual = $this->driver->nodeToString($nodeA);

        $this->assertEquals($expected, $actual, "It should escape parenthesis and commas in the values");
    }

    public function testUnescapingParenthesisAndCommas()
    {
        $treeString = 'A\(A(B\,,C)';
        $nodeA = $this->driver->stringToNode($treeString);
        $children = $nodeA->getChildren();
        $nodeB = $children[0];
        $nodeC = $children[1];

        $this->assertEquals('A(A', $nodeA->getValue(), "It should unescape parenthesis in the values");
        $this->assertEquals('B,',  $nodeB->getValue(), "It should unescape commas in the values");
        $this->assertEquals('C',   $nodeC->getValue(), "It should unescape parenthesis and commas in the values");
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