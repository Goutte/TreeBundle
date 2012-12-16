<?php

namespace Goutte\TreeBundle\Has;

interface Edges
{
    // for both Graph and Vertex
    public function getEdges();
    public function addEdge($edge);
    public function removeEdge($edge);
}
