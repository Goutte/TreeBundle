<?php

namespace Goutte\TreeBundle\Model;

use Goutte\TreeBundle\Is\Node as NodeInterface;

/**
 * Look at Goutte\TreeBundle\Tests\Model\TestNode for a description of each method implementation
 * Look at NodeInterface for comments
 */
abstract class AbstractNode implements NodeInterface
{
    use Node\DefaultBehavior;
}