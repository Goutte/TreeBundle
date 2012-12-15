<?php

namespace Goutte\TreeBundle\Is;

use Goutte\TreeBundle\Is\Node;

interface Driver
{
    /**
     * @param Node $node
     * @return mixed
     */
    public function nodeToString(Node $node);

    /**
     * @param $string
     * @return Node|null
     */
    public function stringToNode($string);
}
