<?php

namespace PhpAbac\Comparison;

class NumericComparison extends AbstractComparison
{
    public function isEqual(int $expected, int $value): bool
    {
        return $expected === $value;
    }

    public function isLesserThan(int $expected, int $value): bool
    {
        return $expected > $value;
    }

    public function isLesserThanOrEqual(int $expected, int $value): bool
    {
        return $expected >= $value;
    }

    public function isGreaterThan(int $expected, int $value): bool
    {
        return $expected < $value;
    }

    public function isGreaterThanOrEqual(int $expected, int $value): bool
    {
        return $expected <= $value;
    }
}
