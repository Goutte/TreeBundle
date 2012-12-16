<?php

namespace Goutte\TreeBundle\Model\Edge;

class Edge
    implements \Goutte\TreeBundle\Has\Vertices
{
    public function getVertices()
    {
        // TODO: Implement getVertices() method.
    }

    public function getEndpoints()
    {
        return $this->getVertices();
    }
}