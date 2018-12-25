<?php

namespace PhpAbac\Comparison;

class StringComparison extends AbstractComparison
{
    /**
     * @param string $expected
     * @param $value
     *
     * @return bool
     */
    public function isEqual(string $expected, $value): bool
    {
        return $expected === $value;
    }

    /**
     * @param string $expected
     * @param $value
     *
     * @return bool
     */
    public function isNotEqual(string $expected, $value): bool
    {
        return !$this->isEqual($expected, $value);
    }
}
