<?php

namespace PhpAbac\Comparison;

class ArrayComparison {
    /**
     * @param string $serializedHaystack
     * @param mixed $needle
     * @return boolean
     */
    public function isIn($serializedHaystack, $needle) {
        return in_array($needle, unserialize($serializedHaystack));
    }
    
    /**
     * @param string $serializedHaystack
     * @param mixed $needle
     * @return boolean
     */
    public function isNotIn($serializedHaystack, $needle) {
        return !in_array($needle, unserialize($serializedHaystack));
    }
}
