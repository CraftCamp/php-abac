<?php

namespace PhpAbac\Comparison;

class ArrayComparison extends AbstractComparison
{
    public function isIn(array $haystack, $needle): bool
    {
        return in_array($needle, $haystack);
    }

    public function isNotIn(array $haystack, $needle): bool
    {
        return !$this->isIn($haystack, $needle);
    }

    public function intersect(array $array1, array $array2): bool
    {
        return count(array_intersect($array1, $array2)) > 0;
    }

    public function doNotIntersect(array $array1, array $array2): bool
    {
        return !$this->intersect($array1, $array2);
    }

    public function contains(array $policyRuleAttributes, array $attributes, array $extraData = []): bool
    {
        foreach ($extraData['attribute']->getValue() as $attribute) {
            $result = true;
            // For each attribute, we check the whole rules set
            foreach ($policyRuleAttributes as $pra) {
                $attributeData = $pra->getAttribute();
                $attributeData->setValue(
                    $this->comparisonManager->getAttributeManager()->retrieveAttribute($attributeData, $extraData['user'], $attribute)
                );
                // If one field is not matched, the whole attribute is rejected
                if (!$this->comparisonManager->compare($pra, true)) {
                    //var_dump($attributeData->getName(), $attributeData->getValue(), $pra->getValue());
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
