<?php

namespace Goutte\TreeBundle\Model;

use Goutte\TreeBundle\Is\Node as NodeInterface;

/**
 * You can extend this but you can/should make your own base tree using the same interface(s) and trait(s),
 * so you don't waste your only chance at inheritance
 */
class Node implements NodeInterface
{
    use Node\DefaultBehavior;
}