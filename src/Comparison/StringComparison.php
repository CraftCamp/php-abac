<?php

namespace PhpAbac\Comparison;

class StringComparison extends AbstractComparison
{
    public function isEqual(string $expected, $value): bool
    {
        return $expected === $value;
    }

    public function isNotEqual(string $expected, $value): bool
    {
        return !$this->isEqual($expected, $value);
    }
}
