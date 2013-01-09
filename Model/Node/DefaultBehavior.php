<?php

namespace Goutte\TreeBundle\Model\Node;

trait DefaultBehavior
{
    use StringLabel;
    use SingleParentAndMultipleChildren;
    use RealRandomMethods;
}