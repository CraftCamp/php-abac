<?php

namespace PhpAbac\Comparison;

class DateComparison {
    
    /**
     * Return true if the given datetime is between two other datetimes
     * 
     * @param \DateTime $start
     * @param \DateTime $end
     * @param \DateTime $value
     * @return boolean
     */
    public function isBetween(\DateTime $start, \DateTime $end, \DateTime $value) {
        return $start <= $value && $end >= $value;
    }
}