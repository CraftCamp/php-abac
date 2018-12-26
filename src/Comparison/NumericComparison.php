<?php

namespace PhpAbac\Comparison;

class NumericComparison extends AbstractComparison
{
    /**
     * @param int $expected
     * @param int $value
     *
     * @return bool
     */
    public function isEqual(int $expected, int $value): bool
    {
        return $expected === $value;
    }

    /**
     * @param int $expected
     * @param int $value
     *
     * @return bool
     */
    public function isLesserThan(int $expected, int $value): bool
    {
        return $expected > $value;
    }

    /**
     * @param int $expected
     * @param int $value
     *
     * @return bool
     */
    public function isLesserThanOrEqual(int $expected, int $value): bool
    {
        return $expected >= $value;
    }

    /**
     * @param int $expected
     * @param int $value
     *
     * @return bool
     */
    public function isGreaterThan(int $expected, int $value): bool
    {
        return $expected < $value;
    }

    /**
     * @param int $expected
     * @param int $value
     *
     * @return bool
     */
    public function isGreaterThanOrEqual(int $expected, int $value): bool
    {
        return $expected <= $value;
    }
}
