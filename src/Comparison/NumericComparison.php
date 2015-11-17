<?php

namespace PhpAbac\Comparison;

class NumericComparison
{
    /**
     * @param int $expected
     * @param int $value
     *
     * @return bool
     */
    public function isEqual($expected, $value)
    {
        return (int) $expected === (int) $value;
    }

    /**
     * If strict is set to false, equal values will return true.
     * 
     * @param int  $expected
     * @param int  $value
     * @param bool $strict
     *
     * @return bool
     */
    public function isLesserThan($expected, $value, $strict = true)
    {
        return
            ($strict === true)
            ? $expected > $value
            : $expected >= $value
        ;
    }

    /**
     * If strict is set to false, equal values will return true.
     * 
     * @param int  $expected
     * @param int  $value
     * @param bool $strict
     *
     * @return bool
     */
    public function isGreaterThan($expected, $value, $strict = true)
    {
        return
            ($strict === true)
            ? $expected < $value
            : $expected <= $value
        ;
    }
}
