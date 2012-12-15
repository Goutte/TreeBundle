<?php

namespace Goutte\TreeBundle\Tests\TestCase;

interface DriverTestCaseInterface
{
    /**
     * The full name to the Driver class we want to test
     * @return string
     */
    public function getDriverClass();

    /**
     * Provides a tree as string that will stay unchanged as we convert it to Node and back
     * @return array[]
     */
    public function treeAsStringThatStaysTheSameProvider();

    /**
     * Provides two trees as string, the initial and the expected after a back and forth conversion to Node(s)
     * @return array[]
     */
    public function treeAsStringThatConvertsInto();
}