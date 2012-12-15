<?php

namespace Goutte\TreeBundle\Tests\Driver;

use Goutte\TreeBundle\Tests\TestCase\DriverTestCase;

class AsciiTest extends DriverTestCase
{

    public function getDriverClass()
    {
        return 'Goutte\TreeBundle\Driver\Ascii';
    }

    public function treeAsStringThatConvertsInto()
    {
        return array(
            array(<<<EOF
A
+--B
|  +--C
|  +--D
|  |  +--G
|  +--E
+--F
EOF
,<<<EOF
A
+--B
|  +--C
|  +--D
|  |  +--G
|  +--E
+--F
EOF
            ),

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
            ),
        );
    }

}