<?php

namespace PhpAbac\Comparison;

class ArrayComparison {
    /**
     * @param mixed $needle
     * @param array $haystack
     * @return boolean
     */
    public function isIn($needle, $haystack) {
        return in_array($needle, $haystack);
    }
    
    /**
     * @param mixed $needle
     * @param array $haystack
     * @return boolean
     */
    public function isNotIn($needle, $haystack) {
        return !in_array($needle, $haystack);
    }
}
