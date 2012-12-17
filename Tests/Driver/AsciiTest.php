<?php

namespace Goutte\TreeBundle\Tests\Driver;

use Goutte\TreeBundle\Tests\TestCase\DriverTestCase;

class AsciiTest extends DriverTestCase
{

    public function getDriverClass()
    {
        return 'Goutte\TreeBundle\Driver\Ascii';
    }

    public function testConvertToString()
    {
        $nodeA = $this->createNode('A');
        $nodeB = $this->createNode('B');
        $nodeC = $this->createNode('C');
        $nodeD = $this->createNode('D');

        $nodeA->addChild($nodeB);
        $nodeA->addChild($nodeD);
        $nodeB->addChild($nodeC);

        $expected = <<<EOF
A
+--B
|  +--C
+--D
EOF;

        $this->assertEquals($expected, $this->driver->nodeToString($nodeA), "It should properly convert to string");
    }

    public function testEscapingLinebreaksInValues()
    {
        $nodeA = $this->createNode("A");
        $nodeB = $this->createNode("B\nBQ");

        $nodeA->addChild($nodeB);

        $expected = <<<EOF
A
+--B\\nBQ
EOF;

        $actual = $this->driver->nodeToString($nodeA);

        $this->assertEquals($expected, $actual, "It should properly escape linebreaks in values");

        $node = $this->driver->stringToNode($actual);
        $this->assertEquals("B\nBQ", $node->getFirstChild()->getValue(), "It should properly unescape linebreaks in values");
    }

    public function testReservedSymbolsInValues()
    {
        $treeString = <<<EOF
+A
+--B+
|  +--+C+
+---F|
EOF;

        $node = $this->driver->stringToNode($treeString);

        $this->assertEquals('+A', $node->getValue(), "It should get values starting with a +");
        $this->assertEquals('B+', $node->getFirstChild()->getValue(), "It should get values ending with a +");
        $this->assertEquals('+C+', $node->getFirstChild()->getFirstChild()->getValue(), "It should get values surrounded by +");
        $this->assertEquals('-F|', $node->getSecondChild()->getValue(), "It should get values starting with a -");
    }

    public function testGeneratingBigRandomTree()
    {
        $alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $nodes = array();
        for ($i=0 ; $i<strlen($alphabet) ; $i++)
        {
            $char = $alphabet{$i%strlen($alphabet)};
            $node = $this->createNode($char);
            $nodes[] = $node;
            if ($i > 0) $nodes[mt_rand(0,$i-1)]->addChild($node);
        }

        echo "Generating random tree :\n";
        echo $this->driver->nodeToString($nodes[0]);
    }


    public function treeAsStringThatConvertsIntoProvider()
    {
        return array(
            array('A','A'),
        );
    }

    public function treeAsStringThatStaysTheSameProvider()
    {
        return array(
            array('Root'),
            array(<<<EOF
A
+--B
|  +--C
|  +--D
|  |  +--G
|  +--E
+--F
EOF
            ), // it should work with complex trees
            array(<<<EOF
A
+--B
+--C
   +--D
      +--E
EOF
            ), // it should omit the | if there is only one child
            array(<<<EOF
Antoine
+--Bilbo
   +--Corentin
EOF
            ), // it should work with trees with ~lenghtier values
        );
    }

}