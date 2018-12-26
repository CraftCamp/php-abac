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
    public function boolAnd(bool $expected, bool $value): bool
    {
        return $expected && $value;
    }

    /**
     * @param $expected
     * @param $value
     *
     * @return bool
     */
    public function boolOr($expected, $value): bool
    {
        return $expected || $value;
    }

    /**
     * @param $expected
     * @param $value
     *
     * @return bool
     */
    public function isNull($expected, $value): bool
    {
        return $value === null;
    }

    /**
     * @param $expected
     * @param $value
     *
     * @return bool
     */
    public function isNotNull($expected, $value): bool
    {
        return $value !== null;
    }
}
