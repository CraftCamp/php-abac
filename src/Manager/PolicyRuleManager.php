<?php

namespace PhpAbac\Manager;

use PhpAbac\Model\PolicyRule;
use PhpAbac\Model\PolicyRuleAttribute;

class PolicyRuleManager
{
    /** @var \PhpAbac\Manager\AttributeManager * */
    private $attributeManager;
    /** @var array * */
    private $rules;

    /**
     * @param \PhpAbac\Manager\AttributeManager $attributeManager
     * @param array $rules
     */
    public function __construct(AttributeManager $attributeManager, $rules)
    {
        $this->attributeManager = $attributeManager;
        $this->rules = $rules;
    }

    /**
     * @param string $ruleName
     * @param object $user
     * @param object $resource
     * @return PolicyRule[]
     * @throws \InvalidArgumentException
     */
    public function getRule($ruleName, $user, $resource)
    {
        if (!isset($this->rules[$ruleName])) {
            throw new \InvalidArgumentException('The given rule "' . $ruleName . '" is not configured');
        }

        // force to treat always arrays
        if (array_key_exists('attributes', $this->rules[$ruleName])) {
            $this->rules[$ruleName] = [$this->rules[$ruleName]];
        }


        $rule_a = [];
        foreach ($this->rules[$ruleName] as $rule) {
            $Policy =
                (new PolicyRule())
                    ->setName($ruleName);
            // For each policy rule attribute, the data is formatted
            foreach ($this->processRuleAttributes($rule['attributes'], $user, $resource) as $pra) {
                $Policy->addPolicyRuleAttribute($pra);
            }
            $rule_a[] = $Policy;
        }
        return $rule_a;
    }

    /**
     * This method is meant to convert attribute data from array to formatted policy rule attribute
     *
     * @param array $attributes
     * @param object $user
     * @param object $resource
     */
    public function processRuleAttributes($attributes, $user, $resource)
    {
        foreach ($attributes as $attributeName => $attribute) {
            $pra = (new PolicyRuleAttribute())
                ->setAttribute($this->attributeManager->getAttribute($attributeName))
                ->setComparison($attribute['comparison'])
                ->setComparisonType($attribute['comparison_type'])
                ->setValue((isset($attribute['value'])) ? $attribute['value'] : null);
            $this->processRuleAttributeComparisonType($pra, $user, $resource);
            // In the case the user configured more keys than the basic ones
            // it will be stored as extra data
            foreach ($attribute as $key => $value) {
                if (!in_array($key, ['comparison', 'comparison_type', 'value'])) {
                    $pra->addExtraData($key, $value);
                }
            }
            // This generator avoid useless memory consumption instead of returning a whole array
            yield $pra;
        }
    }

    /**
     * This method is meant to set appropriated extra data to $pra depending on comparison type
     *
     * @param PolicyRuleAttribute $pra
     * @param object $user
     * @param object $resource
     */
    public function processRuleAttributeComparisonType(PolicyRuleAttribute $pra, $user, $resource)
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
