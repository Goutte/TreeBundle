TreeBundle
==========

Provides a service for serializing and unserializing nodes,
to and from strings such as `A(B(),C(D()))`.

Drivers (dumb!) provided :
  - simple parenthesis : `A(B(),C(D()))`
  - simple (very!) [timbre](https://github.com/mohayonao/timbre) : `T("*",T(6),T("sin",T(55.2)))`

See the [Tests](https://github.com/Goutte/TreeBundle/tree/master/Driver) for more examples of what the Drivers support.

Provides Node interface and abstract class for your rooted tree-able models.

Also provides simple Drivers to import and export your Nodes from and to String representation,
given their `value` is serializable.



How to Use
==========

Add this bundle to your project using composer

    composer require goutte/tree-bundle


Service
-------

Use the service from the container :

``` php
    // get the serializer service
    $serializer = $container->get('goutte_tree.serializer');

    // this will create the nodes and return the root node
    $node = $serializer->toNode('root(childA(),childB(grandchild(C)))');

    // this will return the string for the subtree below the passed node
    $string = $serializer->toString($node);
```


See `Goutte\TreeBundle\Is\Node` for a list of the methods provided by the abstract class `Goutte\TreeBundle\Model\Node`.


Using your own Node
-------------------

_This will be subject to heavy changes in the v2.0_

### Extending

``` php
    use Goutte\TreeBundle\Model\Node as AbstractNode;
    class MyNode extends AbstractNode {
        // ...
    }
```


### Implementing

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

Add to your `services.xml` :

``` xml
    <service id="goutte_tree.driver.mydriver" class="MyVendor\MyBundle\Driver\MyDriver">
        <argument>%goutte_tree.node.class%</argument>
        <tag name="goutte_tree.driver" />
    </service>
```

Configure the service to use your custom driver with `->useDriver()` :

``` php
    // Get the service
    $serializer = $container->get('goutte_tree.serializer');
    // Tell it to use your driver
    $serializer->useDriver('mydriver');

    // ...
```

You may skip usage of `->useDriver()` by telling the service to use your driver as default in the `service.xml` :

``` xml
    <tag name="goutte_tree.driver" default="true" />
```


Testing
=======

Run composer with the `--dev` option so that the autoloader is created and the needed sf2 DIC classes are autoloaded.
_Oddly enough, when I tried to install with `--test` and `require-test` in the `composer.json`, I was sent packing. (pun intended)_

    composer install --dev

Then, simply run

    phpunit


Pitfalls
========

The nodes values are not escaped by the drivers (yet), so no `(`, `)` or `,`


RoadMap
=======

By order of priority, feel free to *fork'n work* !


v1.0
----

- ~~TreeIntegrityException~~
- ~~DriverException~~
- ~~isDescendantOf~~
- ~~isAncestorOf~~
- ~~Path finding~~
- ~~Tree integrity tests~~
- ~~DIC for Drivers~~
- Documentation
- Cleanup and refactoring


v2.0
----

- AsciiDriver for multiline ascii trees, structured as the commented tree in the Tests
- Refactor Node into multiple Interfaces and Traits
- Tree walking for Tree flattening
