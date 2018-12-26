<?php

namespace PhpAbac\Comparison;

class UserComparison extends AbstractComparison
{
    /**
     * @param string $attributeId
     * @param $value
     * @param array $extraData
     *
     * @return bool
     */
    public function isFieldEqual(string $attributeId, $value, array $extraData = []): bool
    {
        $attributeManager = $this->comparisonManager->getAttributeManager();
        // Create an attribute out of the extra data we have and compare its retrieved value to the expected one
        $result =  $attributeManager->retrieveAttribute(
                                        $attributeManager->getAttribute($attributeId),
                                        $extraData['user']
        );
        return $result === $value;
    }
}
