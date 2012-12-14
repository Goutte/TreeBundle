<?php

namespace Goutte\TreeBundle\Is;

use Goutte\TreeBundle\Is\Node;

interface Driver
{
    public function nodeToString(Node $node);
    public function stringToNode($string);
    public function getName();
}
