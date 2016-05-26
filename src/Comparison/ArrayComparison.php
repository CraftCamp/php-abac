<?php

namespace PhpAbac\Comparison;

class ArrayComparison
{
    /**
     * @param array $haystack
     * @param mixed  $needle
     *
     * @return bool
     */
    public function isIn($haystack, $needle)
    {
        return in_array($needle, $haystack);
    }

    /**
     * @param array $haystack
     * @param mixed  $needle
     *
     * @return bool
     */
    public function isNotIn($haystack, $needle)
    {
        return !$this->isIn($haystack, $needle);
    }

    /**
     * @param array $array1
     * @param array $array2
     *
     * @return bool
     */
    public function intersect($array1, $array2)
    {
        return count(array_intersect($array1, $array2)) > 0;
    }

    /**
     * @param array $array1
     * @param array $array2
     *
     * @return bool
     */
    public function doNotIntersect($array1, $array2)
    {
        return !$this->intersect($array1, $array2);
    }
}
