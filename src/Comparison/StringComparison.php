<?php

namespace PhpAbac\Comparison;

class StringComparison extends AbstractComparison
{
    /**
     * @param string $expected
     * @param string $value
     *
     * @return bool
     */
    public function isEqual($expected, $value)
    {
        return $expected === $value;
    }

    /**
     * @param string $expected
     * @param string $value
     *
     * @return bool
     */
    public function isNotEqual($expected, $value)
    {
        return !$this->isEqual($expected, $value);
    }
}
