<?php

namespace PhpAbac\Comparison;

class NumericComparison {
    /**
     * @param integer $expected
     * @param integer $value
     * @return boolean
     */
    public function isEqual($expected, $value) {
        return (int) $expected === (int) $value;
    }
    
    /**
     * If strict is set to false, equal values will return true
     * 
     * @param integer $expected
     * @param integer $value
     * @param boolean $strict
     * @return boolean
     */
    public function isLesserThan($expected, $value, $strict = true) {
        return
            ($strict === true)
            ? $expected > $value
            : $expected >= $value
        ;
    }
    
    /**
     * If strict is set to false, equal values will return true
     * 
     * @param integer $expected
     * @param integer $value
     * @param boolean $strict
     * @return boolean
     */
    public function isGreaterThan($expected, $value, $strict = true) {
        return
            ($strict === true)
            ? $expected < $value
            : $expected <= $value
        ;
    }
}