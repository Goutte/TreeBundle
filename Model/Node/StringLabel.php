<?php

namespace Goutte\TreeBundle\Model\Node;

trait StringLabel
{
    /**
     * The label held by the Node
     * @var mixed
     */
    protected $label = '';

    function __toString()
    {
        return $this->getLabel();
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel($label)
    {
        $this->label = $label;
    }
}