<?php

namespace Goutte\TreeBundle\Is;

interface Tree {

    /**
     * @return int
     */
    public function getLength();

    /**
     * @return Node
     */
    public function getRoot();

}