<?php

namespace Goutte\TreeBundle\Factory;

interface NodeFactoryInterface
{
    /**
     * Instantiates a new Node and fills it with specified $label
     *
     * @param $label
     * @return mixed
     */
    public function createNodeFromLabel($label);
}