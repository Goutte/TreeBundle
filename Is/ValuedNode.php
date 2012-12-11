<?php

namespace Goutte\TreeBundle\Is;

interface ValuedNode extends Node
{
    public function getValue();
    public function setValue($value);
}