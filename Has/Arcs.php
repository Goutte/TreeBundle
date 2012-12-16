<?php

namespace Goutte\TreeBundle\Has;

interface Arcs extends Edges
{
    public function getArcs();
    public function addArc($arc);
    public function removeArc($arc);
}
