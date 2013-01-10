<?php

namespace Goutte\TreeBundle\Is;

use Goutte\TreeBundle\Is\Node;
use Goutte\TreeBundle\Is\Tree;

interface Driver
{
    /**
     * @param Node $tree
     * @return mixed
     */
    public function treeToString(Node $tree);

    /**
     * @param $string
     * @return Tree|null
     */
    public function stringToTree($string);
}
