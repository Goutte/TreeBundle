<?php

namespace Goutte\TreeBundle\Is;

use Goutte\TreeBundle\Is\ValuedNode;

interface Driver
{
    public function nodeToString(ValuedNode $node);
    public function stringToNode($string);
}
