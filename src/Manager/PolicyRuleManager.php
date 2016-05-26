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

    /**
     * @param PolicyRule $policyRule
     */
    public function create(PolicyRule $policyRule)
    {
        $this->repository->createPolicyRule($policyRule);

        $attributes = $policyRule->getPolicyRuleAttributes();
        $nbAttributes = count($attributes);

        for ($i = 0; $i < $nbAttributes; ++$i) {
            $this->createPolicyRuleAttribute($policyRule, $attributes[$i]);
        }
    }

    /**
     * The variables used in empty() calls are meant to make this method working with PHP 5.4
     * 
     * @param PolicyRule          $policyRule
     * @param PolicyRuleAttribute $pra
     *
     * @throws \InvalidArgumentException
     */
    public function createPolicyRuleAttribute($policyRule, PolicyRuleAttribute $pra)
    {
        if (!in_array($pra->getType(), ['user', 'object', 'environment'])) {
            throw new \InvalidArgumentException('The attribute type must have the value "user", "object" or "environment"');
        }
        $comparisonType = $pra->getComparisonType();
        if (empty($comparisonType)) {
            throw new \InvalidArgumentException('The attribute must have a comparison type');
        }
        $comparison = $pra->getComparison();
        if (empty($comparison)) {
            throw new \InvalidArgumentException('The attribute must have a comparison');
        }
        $value = $pra->getValue();
        if (empty($value)) {
            throw new \InvalidArgumentException('The attribute must have a value');
        }
        $attribute = $pra->getAttribute();
        if (!is_a($attribute, 'PhpAbac\\Model\\AbstractAttribute')) {
            throw new \InvalidArgumentException('The attribute must be an subclass of AbstractAttribute');
        }
        Abac::get('attribute-manager')->create($attribute);
        $this->repository->createPolicyRuleAttribute($policyRule->getId(), $pra);
        $policyRule->addPolicyRuleAttribute($pra);
    }
}
