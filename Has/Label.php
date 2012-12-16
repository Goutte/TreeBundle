<?php

namespace Goutte\TreeBundle\Has;

interface Label
{
    /**
     * @return string
     */
    public function getLabel();

    /**
     * @param string $label
     */
    public function setLabel($label);
}
