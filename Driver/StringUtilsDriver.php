<?php

namespace Goutte\TreeBundle\Driver;

/**
 * This oughta be moved to a Trait, obviously
 */
class StringUtilsDriver
{
    /**
     * @param mixed $var
     * @return bool
     */
    protected function isNumeric($var)
    {
        return is_numeric($var);
    }

    /**
     * @param mixed $var
     * @return bool
     */
    protected function isBoolean($var)
    {
        return true === $var || false === $var || 'true' === $var || 'false' === $var;
    }

    /**
     * Explodes string using ',' as delimiter, but only get top-level elements, using parenthesis as encapsulation
     * Eg: a,b(c,d),e will return array("a", "b(c,d)", "e")
     *
     * @param $string
     * @return array
     */
    protected function explode($string)
    {
        $depth = 0;
        $positions = array();

        // find the indexes of the topmost delimiters
        for ($i = 0; $i < strlen($string); $i++) {
            switch ($string{$i}) {
                case '(':
                    $depth++;
                    break;
                case ')':
                    $depth--;
                    break;
                case ',':
                    if (0 == $depth) {
                        $positions[] = $i;
                    }
                    break;
            }
        }

        $array = array();
        $child = '';
        for ($i = 0; $i < strlen($string); $i++) {
            if (in_array($i, $positions)) {
                $array[] = $child;
                $child = '';
            } else {
                $child .= $string{$i};
            }
        }
        if ('' != $child) {
            $array[] = $child; // add the chunk after the last delimiter
        }

        return $array;
    }
}