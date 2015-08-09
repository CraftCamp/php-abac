<?php

namespace PhpAbac\Comparison;

class StringComparison {
    /**
     * @param string $expected
     * @param string $value
     */
    public function isEqual($expected, $value) {
        return $expected === $value;
    }
}