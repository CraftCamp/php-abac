<?php

namespace PhpAbac\Comparison;

use PhpAbac\Model\PolicyRuleAttribute;

class ArrayComparison extends AbstractComparison
{
    /**
     * @param array $haystack
     * @param $needle
     *
     * @return bool
     */
    public function isIn(array $haystack, $needle): bool
    {
        return in_array($needle, $haystack);
    }

    /**
     * @param array $haystack
     * @param $needle
     *
     * @return bool
     */
    public function isNotIn(array $haystack, $needle): bool
    {
        return !$this->isIn($haystack, $needle);
    }

    /**
     * @param array $array1
     * @param array $array2
     *
     * @return bool
     */
    public function intersect(array $array1, array $array2): bool
    {
        return count(array_intersect($array1, $array2)) > 0;
    }

    /**
     * @param array $array1
     * @param array $array2
     *
     * @return bool
     */
    public function doNotIntersect(array $array1, array $array2): bool
    {
        return !$this->intersect($array1, $array2);
    }

    /**
     * check is array2 have any values that not presents in array1
     * @param array $array1
     * @param array $array2
     *
     * @return bool
     */
    public function containsDiffs(array $array1, array $array2): bool
    {
        return count(array_diff($array2, $array1)) ? true : false;
    }

    /**
     * check is array2 have all values that presents in array1
     * @param array $array1
     * @param array $array2
     *
     * @return bool
     */
    public function NotContainsDiffs(array $array1, array $array2): bool
    {
        return !count(array_diff($array2, $array1)) ? true : false;
    }

    /**
     * @param array $policyRuleAttributes
     * @param array $attributes
     * @param array $extraData
     *
     * @return bool
     */
    public function contains(array $policyRuleAttributes, array $attributes, array $extraData = []): bool
    {
        foreach ($extraData['attribute']->getValue() as $attribute) {
            $result = true;
            // For each attribute, we check the whole rules set
            foreach ($policyRuleAttributes as $pra) {
                /**
                 * @var PolicyRuleAttribute $pra
                 */
                $attributeData = $pra->getAttribute();
                $attributeData->setValue(
                    $this->comparisonManager->getAttributeManager()->retrieveAttribute(
                                                                                $attributeData,
                                                                                $extraData['user'],
                                                                                $attribute)
                );
                // If one field is not matched, the whole attribute is rejected
                if (!$this->comparisonManager->compare($pra, true)) {
                    $result = false;
                    break;
                }
            }
            // If the result is still true at the end of the attribute check, the rule is enforced
            if ($result === true) {
                return true;
            }
        }
        return false;
    }
}
