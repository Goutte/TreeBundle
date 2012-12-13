TreeBundle
==========

Provides Node interface and abstract class for your tree-able models.

Also provides simple Drivers to import and export your Nodes from and to String representation,
given their `value` is serializable.

Pitfall : the nodes values are not escaped by the drivers (yet), so no `(`, `)` or `,`


How to Use
==========

Add this bundle to your project using composer

    composer require goutte/tree-bundle

See `Goutte\TreeBundle\Is\Node` for a list of the methods provided by the abstract class `Goutte\TreeBundle\Model\Node`.

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

Run composer with the `--dev` option so that the autoloader is created and the needed sf2 DIC classes are autoloaded.
_Oddly enough, when I tried to install with `--test` and `require-test` in the `composer.json`, I was sent packing. (pun intended)_

    composer install --dev

Then, simply run

    phpunit


RoadMap
=======

By order of priority, feel free to *fork'n work* !

v1.0
----

- ~~TreeIntegrityException~~
- ~~DriverException~~
- ~~isDescendantOf~~
- ~~isAncestorOf~~
- ~~Path finding : Node->getPathTo(Node):Node[]~~
- DIC for Drivers
- Tree walking for Tree flattening
- Tree consistency checker (validator?)

v2.0
----

- AsciiDriver for multiline ascii trees, structured as the commented tree in the Tests
- Traits
