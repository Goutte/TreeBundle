<?php

namespace Goutte\TreeBundle\Is;

interface Random {
    public function pickArrayValue ($haystack);
    public function pickInteger ($min = 0, $max = null);
}