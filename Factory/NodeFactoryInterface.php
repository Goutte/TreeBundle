<?php

namespace Goutte\TreeBundle\Factory;

interface NodeFactoryInterface
{
    /**
     * @param $label
     * @return mixed
     */
    public function createNodeFromLabel($label);
}