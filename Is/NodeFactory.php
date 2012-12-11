<?php

namespace Goutte\TreeBundle\Is;

interface NodeFactory {
    public function createNode();
    public function getClass();
}