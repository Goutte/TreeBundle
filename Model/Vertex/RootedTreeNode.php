<?php

namespace Goutte\TreeBundle\Model\Vertex;

class RootedTreeNode extends DirectedVertex
    implements \Goutte\TreeBundle\Has\NoSimpleCycle
{
    use FamilyNotation;
}
