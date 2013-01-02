<?php

namespace Goutte\TreeBundle\Util;

use Goutte\TreeBundle\Is\Random as RandomInterface;

class Random implements RandomInterface
{
    public function pickArrayValue ($haystack)
    {
        if (empty($haystack)) return null;

        $k = array_rand($haystack);

        return $haystack[$k];
    }

    public function pickInteger ($min = 0, $max = null)
    {
        if (null === $max) $max = mt_getrandmax();

        return mt_rand($min, $max);
    }
}