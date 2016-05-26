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
        $this->processRuleAttributes($rule);
        return $rule;
    }
    
    /**
     * @param PolicyRule $rule
     */
    public function processRuleAttributes(PolicyRule $rule) {
        foreach($this->rules[$rule->getName()]['attributes'] as $attributeName => $attribute) {
            $rule->addPolicyRuleAttribute(
                (new PolicyRuleAttribute())
                ->setAttribute($this->attributeManager->getAttribute($attributeName))
                ->setComparison($attribute['comparison'])
                ->setComparisonType($attribute['comparison_type'])
                ->setValue($attribute['value'])
            );
        }
    }
}
