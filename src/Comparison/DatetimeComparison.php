<?php

namespace PhpAbac\Comparison;

class DatetimeComparison
{
    /**
     * Return true if the given formatted datetime is between two other datetimes.
     * 
     * @param \DateTime $start
     * @param \DateTime $end
     * @param \DateTime $datetime
     *
     * @return bool
     */
    public function isBetween(\DateTime $start, \DateTime $end, $datetime)
    {
        return $start <= $datetime && $end >= $datetime;
    }

    /**
     * @param string $format
     * @param \DateTime $datetime
     *
     * @return bool
     */
    public function isMoreRecentThan($format, $datetime)
    {
        return $this->getDatetimeFromFormat("-$format") <= $datetime;
    }

    /**
     * @param string $format
     * @param \DateTime $datetime
     *
     * @return bool
     */
    public function isLessRecentThan($format, $datetime)
    {
        return $this->getDatetimeFromFormat("+$format") >= $datetime;
    }

    /**
     * @param string $format
     *
     * @return \DateTime
     */
    public function getDatetimeFromFormat($format)
    {
        $formats = [
            'Y' => 31104000,
            'M' => 2592000,
            'D' => 86400,
            'H' => 3600,
            'm' => 60,
            's' => 1,
        ];

        $operator = $format{0};
        $format = substr($format, 1);
        $time = 0;

        foreach ($formats as $scale => $seconds) {
            $data = explode($scale, $format);

            if (strlen($format) === strlen($data[0])) {
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
