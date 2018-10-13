<?php

namespace PhpAbac\Manager;

use PhpAbac\Configuration\Configuration;

use PhpAbac\Model\{
    PolicyRule,
    PolicyRuleAttribute
};

class PolicyRuleManager
{
    /** @var AttributeManager * */
    private $attributeManager;
    /** @var array **/
    private $rules = [];

    public function __construct(Configuration $configuration, AttributeManager $attributeManager)
    {
        $this->attributeManager = $attributeManager;
        $this->rules = $configuration->getRules();
    }

    public function getRule(string $ruleName, $user, $resource): array
    {
        if (!isset($this->rules[$ruleName])) {
            throw new \InvalidArgumentException('The given rule "' . $ruleName . '" is not configured');
        }

        // TODO check if this is really useful
        // force to treat always arrays
        if (array_key_exists('attributes', $this->rules[$ruleName])) {
            $this->rules[$ruleName] = [$this->rules[$ruleName]];
        }

        $rules = [];
        foreach ($this->rules[$ruleName] as $rule) {
            $policyRule = (new PolicyRule())->setName($ruleName);
            // For each policy rule attribute, the data is formatted
            foreach ($this->processRuleAttributes($rule['attributes'], $user, $resource) as $pra) {
                $policyRule->addPolicyRuleAttribute($pra);
            }
            $rules[] = $policyRule;
        }
        return $rules;
    }

    /**
     * This method is meant to convert attribute data from array to formatted policy rule attribute
     */
    public function processRuleAttributes(array $attributes, $user, $resource)
    {
        foreach ($attributes as $attributeName => $attribute) {
            $pra = (new PolicyRuleAttribute())
                ->setAttribute($this->attributeManager->getAttribute($attributeName))
                ->setComparison($attribute['comparison'])
                ->setComparisonType($attribute['comparison_type'])
                ->setValue((isset($attribute['value'])) ? $attribute['value'] : null)
                ->setGetterParams(isset($attribute[ 'getter_params' ]) ? $attribute[ 'getter_params' ] : []);
            $this->processRuleAttributeComparisonType($pra, $user, $resource);
            // In the case the user configured more keys than the basic ones
            // it will be stored as extra data
            foreach ($attribute as $key => $value) {
                if (!in_array($key, ['comparison', 'comparison_type', 'value','getter_params'])) {
                    $pra->addExtraData($key, $value);
                }
            }
            // This generator avoid useless memory consumption instead of returning a whole array
            yield $pra;
        }
    }

    /**
     * This method is meant to set appropriated extra data to $pra depending on comparison type
     */
    protected function processRuleAttributeComparisonType(PolicyRuleAttribute $pra, $user, $resource)
    {
        switch ($pra->getComparisonType()) {
            case 'user':
                $pra->setExtraData(['user' => $user]);
                break;
            case 'object':
                $pra->setExtraData(['resource' => $resource]);
                break;
        }
    }
}
