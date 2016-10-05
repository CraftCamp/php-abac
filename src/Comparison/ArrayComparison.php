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
            // For each attribute, we check the whole rules set
            foreach($policyRuleAttributes as $pra) {
                $attributeData = $pra->getAttribute();
                $attributeData->setValue(
                    $this->comparisonManager->getAttributeManager()->retrieveAttribute($attributeData, $extraData['user'], $attribute)
                );
                // If one field is not matched, the whole attribute is rejected
                if(!$this->comparisonManager->compare($pra, true)) {
                    //var_dump($attributeData->getName(), $attributeData->getValue(), $pra->getValue());
                    $result = false;
                    break;
                }
            }
            // If the result is still true at the end of the attribute check, the rule is enforced
            if($result === true) {
                return true;
            }
        }
        return false;
    }
}
