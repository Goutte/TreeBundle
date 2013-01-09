<?php

namespace Goutte\TreeBundle\Model\Node;

use Goutte\TreeBundle\Is\Random as RandomInterface;
use Goutte\TreeBundle\Util\Random;

trait RealRandomMethods
{
    abstract public function getDescendants($includeSelf=false);

    public function getRandomDescendant($includeSelf=false, RandomInterface $random=null)
    {
        $pool = $this->getDescendants($includeSelf);

        if (empty($pool)) {
            return null;
        } else {
            if (empty($random)) $random = new Random();

            return $random->pickArrayValue($pool);
        }
    }
}