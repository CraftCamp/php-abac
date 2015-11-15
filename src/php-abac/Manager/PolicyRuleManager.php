<?php

namespace PhpAbac\Manager;

use PhpAbac\Abac;

use PhpAbac\Model\PolicyRule;
use PhpAbac\Model\PolicyRuleAttribute;

use PhpAbac\Repository\PolicyRuleRepository;

class PolicyRuleManager {
    /** @var PolicyRuleRepository **/
    protected $repository;
    
    public function __construct() {
        $this->repository = new PolicyRuleRepository();
    }
    
    /**
     * @param string $ruleName
     * @return PolicyRule
     * @throws \InvalidArgumentException
     */
    public function getRuleByName($ruleName) {
        if(($rule = $this->repository->findByName($ruleName)) === null) {
            throw new \InvalidArgumentException('The rule "'.$ruleName.'" does not exists');
        }
        return $rule;
    }
    
    /**
     * @param PolicyRule $policyRule
     */
    public function create(PolicyRule $policyRule) {
        $this->repository->createPolicyRule($policyRule);
        
        $attributes = $policyRule->getAttributes();
        $nbAttributes = count($attributes);
        
        for($i = 0; $i < $nbAttributes; ++$i) {
            $this->createPolicyRuleAttribute($policyRule, $attributes[$i]);
        }
    }
    
    /**
     * @param PolicyRule $policyRule
     * @param PolicyRuleAttribute $pra
     * @throws \InvalidArgumentException
     */
    public function createPolicyRuleAttribute($policyRule, PolicyRuleAttribute $pra) {
        if(!in_array($pra->getType(), ['user', 'object', 'environment'])) {
            throw new \InvalidArgumentException('The attribute type must have the value "user", "object" or "environment"');
        }
        if(empty($pra->getComparisonType())) {
            throw new \InvalidArgumentException('The attribute must have a comparison type');
        }
        if(empty($pra->getComparison())) {
            throw new \InvalidArgumentException('The attribute must have a comparison');
        }
        if(empty($pra->getValue())) {
            throw new \InvalidArgumentException('The attribute must have a value');
        }
        if(!is_a($pra->getAttribute(), 'PhpAbac\\Model\\AbstractAttribute')) {
            throw new \InvalidArgumentException('The attribute must be an subclass of AbstractAttribute');
        }
        Abac::get('attribute-manager')->create($pra->getAttribute());
        $this->repository->createPolicyRuleAttribute($policyRule->getId(), $pra);
        $policyRule->addPolicyRuleAttribute($pra);
    }
}