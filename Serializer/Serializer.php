<?php

namespace Goutte\TreeBundle\Serializer;

use Goutte\TreeBundle\Exception\DriverException;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

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

    public function addDriver(DriverInterface $driver, $name, $default=false)
    {
        if (!empty($this->drivers[$name])) {
            throw new InvalidArgumentException("A driver with name '{$name}' is already registered.");
        }

        $this->drivers[$name] = $driver;

        if ($default) {
            $this->useDriver($name);
        }
    }

    protected function findDriver($driverName)
    {
        if (empty($this->drivers[$driverName])) {
            throw new DriverException("No driver named {$driverName} was found.");
        }

        return $this->drivers[$driverName];
    }

    public function useDriver($driverName)
    {
        $this->driver = $this->findDriver($driverName);

        return $this;
    }

    public function getDriver()
    {
        if (null === $this->driver) {
            throw new DriverException("No driver currently used, please call ->useDriver('my_driver') first.");
        }

        return $this->driver;
    }

    public function toString(NodeInterface $tree)
    {
        return $this->getDriver()->treeToString($tree);
    }

    public function toTree($string)
    {
        return $this->getDriver()->stringToTree($string);
    }
}