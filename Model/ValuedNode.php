<?php

namespace Goutte\TreeBundle\Model;

use Goutte\TreeBundle\Is\ValuedNode as ValuedNodeInterface;

abstract class ValuedNode extends Node implements ValuedNodeInterface
{
    protected $value;

    public function getValue()
    {
        return $this->value;
    }
    public function setValue($value)
    {
        $this->value = $value;
    }
}