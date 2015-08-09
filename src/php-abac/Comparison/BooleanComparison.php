<?php

namespace PhpAbac\Comparison;

class BooleanComparison {
    
    /**
     * @param boolean $expected
     * @param boolean $value
     * @return boolean
     */
    public function boolAnd($expected, $value) {
        return $expected && $value;
    }
    
    /**
     * @param boolean $expected
     * @param boolean $value
     * @return boolean
     */
    public function boolOr($expected, $value) {
        return $expected || $value;
    }
}