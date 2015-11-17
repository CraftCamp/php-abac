<?php

namespace PhpAbac\Comparison;

class ArrayComparison
{
    /**
     * @param string $serializedHaystack
     * @param mixed  $needle
     *
     * @return bool
     */
    public function isIn($serializedHaystack, $needle)
    {
        return in_array($needle, unserialize($serializedHaystack));
    }

    /**
     * @param string $serializedHaystack
     * @param mixed  $needle
     *
     * @return bool
     */
    public function isNotIn($serializedHaystack, $needle)
    {
        return !$this->isIn($serializedHaystack, $needle);
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
