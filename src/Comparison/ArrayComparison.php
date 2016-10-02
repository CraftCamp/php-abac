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
     * @param array $extraData
     * @return boolean
     */
    public function contains($policyRuleAttributes, $attributes, $extraData = []) {
        foreach($extraData['attribute']->getValue() as $attribute) {
            $result = true;
            foreach($policyRuleAttributes as $pra) {
                $attributeData = $pra->getAttribute();
                $attributeData->setValue(
                    $this->comparisonManager->getAttributeManager()->retrieveAttribute($attributeData, $extraData['user'], $attribute)
                );
                if(!$this->comparisonManager->compare($pra, true)) {
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
