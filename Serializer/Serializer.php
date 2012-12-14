<?php

namespace Goutte\TreeBundle\Serializer;

use Goutte\TreeBundle\Exception\DriverException;

use Goutte\TreeBundle\Is\Driver as DriverInterface;
use Goutte\TreeBundle\Is\Node as NodeInterface;

/**
 * This converts string to nodes and nodes to string using the specified Driver
 */
class Serializer {

    /**
     * @var DriverInterface[]
     */
    protected $drivers;

    /**
     * @var DriverInterface
     */
    protected $driver;


    public function __construct()
    {
        $this->drivers = array();
    }

    public function addDriver(DriverInterface $driver)
    {
        $this->drivers[] = $driver;
    }

    protected function findDriver($driverName)
    {
        foreach ($this->drivers as $driver)
        {
            if ($driverName === $driver->getName()) {
                return $driver;
            }
        }

        throw new DriverException("No driver named {$driverName} was found.");
    }

    public function useDriver($driverName)
    {
        $this->driver = $this->findDriver($driverName);

        return $this;
    }

    public function getDriver()
    {
        if (null === $this->driver) {
            // todo: first in the list ? default in config ??
            throw new DriverException("No driver currently used, please call ->useDriver('my_driver') first.");
        }

        return $this->driver;
    }

    public function toString(NodeInterface $node)
    {
        return $this->getDriver()->nodeToString($node);
    }

    public function toNode($string)
    {
        return $this->getDriver()->stringToNode($string);
    }
}