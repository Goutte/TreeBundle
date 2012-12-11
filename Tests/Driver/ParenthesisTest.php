<?php

namespace Goutte\TreeBundle\Tests\Driver;

use Goutte\TreeBundle\Tests\TestCase\DriverTestCase;

class ParenthesisTest extends DriverTestCase
{

    public function getDriverClass()
    {
        return 'Goutte\TreeBundle\Driver\Parenthesis';
    }

    public function treeAsStringThatStaysTheSameProvider()
    {
        return array(
            array("Alone()"),
            array("A(B(),C(),D())"),
            array("A(B(),C(D(E(F(G())))),H(),I(),J())"),
            array("*(+(6(),migite.thumb.tip.sphericalCoordinates.ro()),2())"),
        );
    }

}