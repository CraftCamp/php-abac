<?php

namespace PhpAbac\Manager;

use PhpAbac\Abac;
use PhpAbac\Model\PolicyRule;
use PhpAbac\Model\PolicyRuleAttribute;

class PolicyRuleManager
{
    /** @var \PhpAbac\Manager\AttributeManager **/
    private $attributeManager;
    /** @var array **/
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
     *
     * @return PolicyRule
     *
     * @throws \InvalidArgumentException
     */
    public function getRule($ruleName)
    {
        if(!isset($this->rules[$ruleName])) {
            throw new \InvalidArgumentException('The given rule "' . $ruleName . '" is not configured');
        }
        $rule =
            (new PolicyRule())
            ->setName($ruleName)
        ;
        foreach($this->processRuleAttributes($this->rules[$ruleName]['attributes']) as $pra) {
            $rule->addPolicyRuleAttribute($pra);
        }
        return $rule;
    }
    
    /**
     * @param array $attributes
     */
    public function processRuleAttributes($attributes) {
        foreach($attributes as $attributeName => $attribute) {
            $pra = (new PolicyRuleAttribute())
                ->setAttribute($this->attributeManager->getAttribute($attributeName))
                ->setComparison($attribute['comparison'])
                ->setComparisonType($attribute['comparison_type'])
                ->setValue((isset($attribute['value'])) ? $attribute['value'] : null)
            ;
            array_filter($attribute, function($value, $key) use ($pra) {
                if(!in_array($key, ['comparison', 'comparison_type', 'value'])) {
                    $pra->addExtraData($key, $value);
                }
            }, 1);
            yield $pra;
        }
    }
}
