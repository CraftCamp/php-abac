<?php

namespace PhpAbac\Comparison;

class ArrayComparison extends AbstractComparison
{
    /**
     * @param array $haystack
     * @param mixed  $needle
     *
     * @return bool
     */
    public function isIn($haystack, $needle)
    {
        return in_array($needle, $haystack);
    }

    /**
     * @param array $haystack
     * @param mixed  $needle
     *
     * @return bool
     */
    public function isNotIn($haystack, $needle)
    {
        return !$this->isIn($haystack, $needle);
    }

    /**
     * @param array $array1
     * @param array $array2
     *
     * @return bool
     */
    public function intersect($array1, $array2)
    {
        return count(array_intersect($array1, $array2)) > 0;
    }

    /**
     * @param array $array1
     * @param array $array2
     *
     * @return bool
     */
    public function doNotIntersect($array1, $array2)
    {
        return !$this->intersect($array1, $array2);
    }
    
    /**
     * @param array $policyRuleAttributes
     * @param array $attributes
     * @return boolean
     */
    public function contains($policyRuleAttributes, $attributes, $extraData = []) {
        foreach($extraData['attribute']->getValue() as $attribute) {
            $result = true;
            foreach($policyRuleAttributes as $pra) {
                $attributeData = $pra->getAttribute();
                if(!$this->comparisonManager->compare(
                    $pra->getComparisonType(),
                    $pra->getComparison(),
                    $pra->getValue(),
                    $this->comparisonManager->getAttributeManager()->retrieveAttribute($attributeData, $extraData['user'], $attribute)
                )) {
                    $result = false;
                    break;
                }
            }
            if($result === true) {
                return true;
            }
        }
        return false;
    }
}
