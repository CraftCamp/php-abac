<?php

namespace PhpAbac\Comparison;

class BooleanComparison extends AbstractComparison
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
    
    /**
     * @param mixed $expected
     * @param mixed $value
     */
    public function isNull($expected, $value)
    {
        return $value === null;
    }
    
    /**
     * @param mixed $expected
     * @param mixed $value
     */
    public function isNotNull($expected, $value) {
        return $value !== null;
    }
}
