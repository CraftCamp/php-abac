<?php

namespace PhpAbac\Comparison;

class DateComparison {
    
    /**
     * Return true if the given formatted datetime is between two other datetimes
     * 
     * @param \DateTime $start
     * @param \DateTime $end
     * @param string $value
     * @return boolean
     */
    public function isBetween(\DateTime $start, \DateTime $end, $value) {
        $datetime = new \DateTime($value);
        return $start <= $datetime && $end >= $datetime;
    }
    
    /**
     * @param string $format
     * @param string $value
     * @return boolean
     */
    public function isMoreRecentThan($format, $value) {
        return $this->getDatetimeFromFormat("-$format") <= new \DateTime($value);
    }
    
    /**
     * @param string $format
     * @param string $value
     * @return boolean
     */
    public function isLessRecentThan($format, $value) {
        return $this->getDatetimeFromFormat("+$format") >= new \DateTime($value);
    }
    
    /**
     * @param string $format
     * @return \DateTime
     */
    public function getDatetimeFromFormat($format) {
        $formats = [
            'Y' => 31104000,
            'M' => 2592000,
            'D' => 86400,
            'H' => 3600,
            'm' => 60,
            's' => 1
        ];
        
        $operator = $format{0};
        $format = substr($format, 1);
        $time = 0;
        
        foreach($formats as $scale => $seconds) {
            $data = explode($scale, $format);
            
            if(strlen($format) === strlen($data[0])) {
                continue;
            }
            $time += $data[0] * $seconds;
            // Remaining format string
            $format = $data[1];
        }
        return
            ($operator === '+')
            ? (new \DateTime())->setTimestamp((time() + $time))
            : (new \DateTime())->setTimestamp((time() - $time))
        ;
    }
}