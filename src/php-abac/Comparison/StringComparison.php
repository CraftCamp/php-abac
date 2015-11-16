<?php

namespace PhpAbac\Comparison;

class StringComparison {
    /**
     * @param string $expected
     * @param string $value
     * @return boolean
     */
    public function isEqual($expected, $value) {
        return $expected === $value;
    }
    
    /**
     * @param string $expected
     * @param string $value
     * @return boolean
     */
    public function isNotEqual($expected, $value) {
        return !$this->isEqual($expected, $value);
    }
}