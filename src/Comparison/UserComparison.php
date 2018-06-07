<?php

namespace PhpAbac\Comparison;

class UserComparison extends AbstractComparison
{
    public function isFieldEqual(string $attributeId, $value, array $extraData = []): bool
    {
        $attributeManager = $this->comparisonManager->getAttributeManager();
        // Create an attribute out of the extra data we have and compare its retrieved value to the expected one
        return $attributeManager->retrieveAttribute(
            $attributeManager->getAttribute($attributeId),
            $extraData['user']
        ) === $value;
    }
}
