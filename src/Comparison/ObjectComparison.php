<?php

namespace PhpAbac\Comparison;

use PhpAbac\Model\Attribute;

class ObjectComparison extends AbstractComparison
{
    /**
     * @param string $attributeId
     * @param mixed $value
     * @param array $extraData
     * @return boolean
     */
    public function isFieldEqual($attributeId, $value, $extraData = []) {
        $attributeManager = $this->comparisonManager->getAttributeManager();
        // Create an attribute out of the extra data we have and compare its retrieved value to the expected one
        return $attributeManager->retrieveAttribute(
            $attributeManager->getAttribute($attributeId),
            null,
            $extraData['resource']
        ) === $value;
    }
}
