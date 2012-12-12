TreeBundle
==========

Provides Node interface and abstract class for your tree-able models.

Also provides simple Drivers to import and export your Nodes from and to String representation,
given their `value` is serializable.

Pitfall : the nodes values are not escaped by the drivers (yet), so no `(`, `)` or `,`


How to Use
==========

See `Goutte\TreeBundle\Is\Node` for a list of the methods provided.

Extending
---------

``` php
    use Goutte\TreeBundle\Model\Node as AbstractNode;
    class MyNode extends AbstractNode {
        // ...
    }
```

Implementing
------------

``` php
    use Goutte\TreeBundle\Is\Node as NodeInterface;
    class MyNode implements NodeInterface {
        // ...
    }
```

Writing a Driver
----------------

Implement `Goutte\TreeBundle\Is\Driver` as follows :

``` php
    use Goutte\TreeBundle\Is\Driver;
    class MyDriver implements Driver {
        // ...
    }
```

Extend `Goutte\TreeBundle\Factory\NodeFactory` as follows :

``` php
    use Goutte\TreeBundle\Factory\NodeFactory as AbstractNodeFactory;
    class NodeFactory extends AbstractNodeFactory
    {
        public function getClass()
        {
            return 'MyVendor\MyBundle\Model\MyNode';
        }
    }
```


Testing
=======

Run composer so that the autoloader is created

    composer.phar install --dev

Then, simply run

    phpunit


Todo
====

By order of priority, feel free to *fork'n work* !

- ~~TreeIntegrityException~~
- ~~DriverException~~
- ~~isDescendantOf~~
- isAncestorOf
- AsciiDriver
- DIC for Factories and Drivers
- Tree walking
- Traits
