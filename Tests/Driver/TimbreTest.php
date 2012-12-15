<?php

namespace Goutte\TreeBundle\Tests\Driver;

use Goutte\TreeBundle\Tests\TestCase\DriverTestCase;

class TimbreTest extends DriverTestCase
{

    public function getDriverClass()
    {
        return 'Goutte\TreeBundle\Driver\Timbre';
    }

    public function treeAsStringThatConvertsIntoProvider()
    {
        return array(
            array('T ("*", T(6), T( 9 ) )','T("*",T(6),T(9))'), // it should trim unnecessary spaces
            array("T(\"*\",\nT(6),\nT(9))",'T("*",T(6),T(9))'), // it should understand and trim multiline tree strings
            array(<<<EOF
T ("*",
    T(6),
    T(9)
)
EOF
,'T("*",T(6),T(9))'), // it should understand and trim multiline tree strings

        );
    }

    public function treeAsStringThatStaysTheSameProvider()
    {
        return array(
            array('T(0)'),
            array('T(666.999)'),
            array('T(true)'),
            array('T(false)'),
            array('T("*",T(6),T(9))'),
            array('T("+",T("sin",T(523.35)),T("sin",T(659.25)),T("sin",T(783.99)))'),
        );
    }

}