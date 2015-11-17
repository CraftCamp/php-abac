<?php

namespace PhpAbac\Comparison;

class BooleanComparison
{
    /**
     * @param bool $expected
     * @param bool $value
     *
     * @return bool
     */
    public function boolAnd($expected, $value)
    {
        return $expected && $value;
    }

    /**
     * @param bool $expected
     * @param bool $value
     *
     * @return bool
     */
    public function boolOr($expected, $value)
    {
        return $expected || $value;
    }
}
