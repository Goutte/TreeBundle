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
}