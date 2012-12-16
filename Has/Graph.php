<?php

namespace Goutte\TreeBundle\Has;

interface Graph
{
    /**
     * @return Graph
     */
    public function getGraph();

    /**
     * @param Graph $graph
     */
    public function setGraph($graph);
}
