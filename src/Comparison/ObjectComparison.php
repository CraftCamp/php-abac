<?php

namespace PhpAbac\Comparison;

class ObjectComparison extends AbstractComparison
{
    public function isFieldEqual(string $attributeId, $value, array $extraData = []): bool
    {
        $attributeManager = $this->comparisonManager->getAttributeManager();
        // Create an attribute out of the extra data we have and compare its retrieved value to the expected one
        return $attributeManager->retrieveAttribute(
            $attributeManager->getAttribute($attributeId),
            null,
            $extraData['resource']
        ) === $value;
    }
}
