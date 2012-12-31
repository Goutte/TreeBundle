TreeBundle
==========

Provides a service for serializing and unserializing nodes, to and from strings such as `A(B,C(D))`.

Drivers provided :
  - Parenthesis : `A(B,C(D))`
  - Simple (dumb!) [timbre](https://github.com/mohayonao/timbre) : `T("*",T(6),T("sin",T(55.2)))`
  - Ascii

        A
        +--B
        |  +--C
        |  +--D
        |     +--E
        +--Fork

See the [Tests](https://github.com/Goutte/TreeBundle/tree/master/Tests/Driver) for more examples of what the Drivers support.

Also provides a Node interface and an abstract class for your models.


How to Use
==========

This is *not* for storing nested sets in the database, if that is what you are looking for you should be looking at
[Doctrine Extensions](https://github.com/l3pp4rd/DoctrineExtensions).
The original purpose of this bundle is to provide a toolset for reading/writing functional code,
or configuring the hierarchy of a menu in plain text for example.

Feel free to extend it to suit your needs, though.

Install
-------

Add this bundle to your project using composer

    composer require goutte/tree-bundle


Service
-------

Use the service from the container :

``` php
    // get the serializer service
    $serializer = $container->get('goutte_tree.serializer');

    // this will create the nodes and return the root node
    $rootNode = $serializer->toNode('root(childA,childB(grandchildC))');

    // this will return the string for the subtree below the passed node
    $string = $serializer->toString($rootNode); // returns 'root(childA,childB(grandchildC))'

    // ... or use another driver
    $string = $serializer->useDriver('ascii')->toString($rootNode);
    // will return:
    // root
    // +--childA
    // +--childB
    //    +--grandchildC
```


See `Goutte\TreeBundle\Is\Node` for a list of the methods provided by the abstract class `Goutte\TreeBundle\Model\AbstractNode`.


Using your own Node
-------------------

_This will be subject to heavy changes in the v2.0, see the wip in the [trait branch](https://github.com/Goutte/TreeBundle/tree/traits)_

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

Then, configure the service to use your own Node class.


Writing a Driver
----------------

Implement `Goutte\TreeBundle\Is\Driver` as follows :

``` php
    use Goutte\TreeBundle\Is\Driver;
    class MyDriver implements Driver {
        public function __construct($nodeClass)
        {
            $this->nodeClass = $nodeClass;
        }
        // ...
    }
```

The `__construct` part is optional but is useful to get the Node class because the driver needs to create Nodes.
If you omit it you must also omit the `<argument>` part in the service definition below.

Add your driver to your `services.xml`, and tag it `goutte_tree.driver` :

``` xml
    <service id="goutte_tree.driver.mydriver" class="MyVendor\MyBundle\Driver\MyDriver" public="false">
        <argument>%goutte_tree.node.class%</argument>
        <tag name="goutte_tree.driver" />
    </service>
```

**(warn)** The service id will define the driver alias, so it needs to start with `goutte_tree.driver.`.
It is not the documented way of doing such a thing (which would be having an alias attribute in the tag), but it is a bit DRYer.
This may be subject to change later, I'm still making up my mind.

Configure the service to use your custom driver with `->useDriver()` :

``` php
    // Get the service, tell it to use your driver
    $serializer = $container->get('goutte_tree.serializer')->useDriver('mydriver');
```

You may skip usage of `->useDriver()` by telling the service to use your driver as default in the `services.xml` :

``` xml
    <tag name="goutte_tree.driver" default="true" />
```


Testing
=======

This bundle is ruthlessly tested, except for some service configuration exceptions (if you know how to test these, I'm all ears!)

Run composer with the `--dev` option so that the autoloader is created and the needed sf2 DIC classes are autoloaded.
_Oddly enough, when I tried to install with `--test` and `require-test` in the `composer.json`, I was sent packing. (pun intended)_

    composer install --dev

Then, simply run

    phpunit


Pitfalls
========

### Parenthesis Driver

Nodes with empty label can convert to string, but not back to node.

Eg: `A(B,C)` tree, if nodes' labels are emptied, will convert back to `(,)`

Envisioned solutions :
  - Throw on toString conversion if label is empty -> loss of feature
  - Tweak the toNode regex to allow empty labels -> disturbing as `A()` will create two nodes for example

### Timbre Driver

The nodes labels are not escaped by the driver, so no `(`, `)` or `,`.
As these characters are not used by Timbre's nodes, this should not be a problem.

Numerical labels must be encapsulated in `T()`, like so : `T(66.2)`.

### Ascii Driver

Linebreaks will be (un)escaped so that labels stay on one line in the string representation.

Because the reader expects *exactly 2* `-` as indentation, the Node label will hold the extra `-` if you add more.

Eg:

    A
    +---B

=> The child node's label will be `-B`, not `B`.


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
- ~~Documentation~~
- ~~Cleanup~~

v1.5
----

- ~~Smarter parenthesis driver~~
- ~~Smarter timbre driver~~
- ~~AsciiDriver for multiline ascii trees~~
- ~~Test&Fix the multiline issue in labels~~
- ~~Node replacement with ->replaceBy()~~

v1.6
----

- ~~Node cloning~~

v2.0
----

_These have no timetable, don't wait for them_

- Graph theory, see BLACKBOARD
- Tree walking for Tree flattening

