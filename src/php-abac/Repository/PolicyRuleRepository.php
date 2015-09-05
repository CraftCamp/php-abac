<?php

namespace PhpAbac\Repository;

use PhpAbac\Model\Attribute;
use PhpAbac\Model\EnvironmentAttribute;
use PhpAbac\Model\PolicyRule;
use PhpAbac\Model\PolicyRuleAttribute;

class PolicyRuleRepository extends Repository {
    
    /**
     * @param string $name
     * @return PolicyRule|null
     */
    public function findByName($name) {
        $statement = $this->query(
            'SELECT pr.id, pr.created_at, pr.updated_at, pra.type, pra.comparison_type, ' .
            'pra.comparison, pra.value, ad.id AS attribute_id, ad.name AS attribute_name, ' .
            'a.table_name, a.column_name, a.criteria_column, ' .
            'ea.variable_name, ' .
            'ad.created_at AS attribute_created_at, ad.updated_at AS attribute_updated_at ' .
            'FROM abac_policy_rules pr ' .
            'LEFT JOIN abac_policy_rules_attributes pra ON pra.policy_rule_id = pr.id ' .
            'LEFT JOIN abac_attributes_data ad ON ad.id = pra.attribute_id ' .
            'LEFT JOIN abac_attributes a ON a.id = ad.id ' .
            'LEFT JOIN abac_environment_attributes ea ON ea.id = ad.id ' .
            'WHERE pr.name = :name', [
            'name' => $name
        ]);
        
        // To initialize policy rule properties, we need to do a first fetch
        if(!($data = $statement->fetch())) {
            return null;
        }
        $policyRule = 
            (new PolicyRule())
            ->setId($data['id'])
            ->setName($name)
            ->setCreatedAt(new \DateTime($data['created_at']))
            ->setUpdatedAt(new \DateTime($data['updated_at']))
        ;
        // If there is no attributes for this rule, we simply return it
        if($data['attribute_id'] === null) {
            return $policyRule;
        }
        $this->fetchPolicyRuleAttribute($policyRule, $data);
        // Then we fetch the remaining rows, corresponding to attributes
        while($data = $statement->fetch()) {
            $this->fetchPolicyRuleAttribute($policyRule, $data);
        }
        return $policyRule;
    }
    
    /**
     * @param PolicyRule $policyRule
     * @param array $data
     */
    public function fetchPolicyRuleAttribute($policyRule, $data) {
        $attribute = 
            (!empty($data['variable_name']))
            ?
                (new EnvironmentAttribute())
                ->setId($data['attribute_id'])
                ->setName($data['attribute_name'])
                ->setVariableName($data['variable_name'])
                ->setCreatedAt(new \DateTime($data['attribute_created_at']))
                ->setUpdatedAt(new \DateTime($data['attribute_updated_at']))
            :
                (new Attribute())
                ->setId($data['attribute_id'])
                ->setName($data['attribute_name'])
                ->setTable($data['table_name'])
                ->setColumn($data['column_name'])
                ->setCriteriaColumn($data['criteria_column'])
                ->setCreatedAt(new \DateTime($data['attribute_created_at']))
                ->setUpdatedAt(new \DateTime($data['attribute_updated_at']))
        ;
        $policyRule
            ->addPolicyRuleAttribute(
                (new PolicyRuleAttribute())
                ->setType($data['type'])
                ->setComparisonType($data['comparison_type'])
                ->setComparison($data['comparison'])
                ->setValue($data['value'])
                ->setAttribute($attribute)
            )
        ;
    }
    
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