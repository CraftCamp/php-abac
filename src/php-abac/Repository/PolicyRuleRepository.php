<?php

namespace PhpAbac\Repository;

use PhpAbac\Model\Attribute;
use PhpAbac\Model\PolicyRule;
use PhpAbac\Model\PolicyRuleAttribute;

class PolicyRuleRepository extends Repository {
    
    /**
     * @param string $name
     * @return PolicyRule
     */
    public function createPolicyRule($name) {
        $datetime = new \DateTime();
        $formattedDatetime = $datetime->format('Y-m-d H:i:s');
        
        $this->insert(
            'INSERT INTO abac_policy_rules(name, created_at, updated_at) ' .
            'VALUES(:name, :created_at, :updated_at)', [
            'name' => $name,
            'created_at' => $formattedDatetime,
            'updated_at' => $formattedDatetime
        ]);
        return
            (new PolicyRule())
            ->setId($this->connection->lastInsertId('abac_policy_rules'))
            ->setName($name)
            ->setCreatedAt($datetime)
            ->setUpdatedAt($datetime)
        ;
    }
    
    /**
     * @param integer $policyRuleId
     * @param Attribute $attribute
     * @param string $comparison
     * @param string $value
     * @return PolicyRuleAttribute
     */
    public function createPolicyRuleAttribute($policyRuleId, Attribute $attribute, $comparison, $value) {
        $this->insert(
            'INSERT INTO abac_policy_rules_attributes(policy_rule_id, attribute_id, comparison, value) ' .
            'VALUES(:policy_rule_id, :attribute_id, :comparison, :value)', [
            'policy_rule_id' => $policyRuleId,
            'attribute_id' => $attribute->getId(),
            'comparison' => $comparison,
            'value' => $value
        ]);
        return
            (new PolicyRuleAttribute())
            ->setAttribute($attribute)
            ->setComparison($comparison)
            ->setValue($value)
        ;
    }
}