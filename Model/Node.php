<?php

namespace Goutte\TreeBundle\Model;

use Goutte\TreeBundle\Is\Node as NodeInterface;

// Test ?
class Node implements NodeInterface {
    use Node\DefaultBehavior;
}