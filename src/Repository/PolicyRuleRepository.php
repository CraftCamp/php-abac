<?php

namespace PhpAbac\Repository;

use PhpAbac\Model\Attribute;
use PhpAbac\Model\EnvironmentAttribute;
use PhpAbac\Model\PolicyRule;
use PhpAbac\Model\PolicyRuleAttribute;

class PolicyRuleRepository extends Repository
{
    /**
     * @param string $name
     *
     * @return PolicyRule|null
     */
    public function findByName($name)
    {
        $statement = $this->query(
            'SELECT pr.id, pr.created_at, pr.updated_at, pra.type, pra.comparison_type, '.
            'pra.comparison, pra.value, ad.id AS attribute_id, ad.name AS attribute_name, ad.slug, '.
            'a.table_name, a.column_name, a.criteria_column, '.
            'ea.variable_name, '.
            'ad.created_at AS attribute_created_at, ad.updated_at AS attribute_updated_at '.
            'FROM abac_policy_rules pr '.
            'LEFT JOIN abac_policy_rules_attributes pra ON pra.policy_rule_id = pr.id '.
            'LEFT JOIN abac_attributes_data ad ON ad.id = pra.attribute_id '.
            'LEFT JOIN abac_attributes a ON a.id = ad.id '.
            'LEFT JOIN abac_environment_attributes ea ON ea.id = ad.id '.
            'WHERE pr.name = :name', [
            'name' => $name,
        ]);

        // To initialize policy rule properties, we need to do a first fetch
        if (!($data = $statement->fetch())) {
            return;
        }
        $policyRule =
            (new PolicyRule())
            ->setId($data['id'])
            ->setName($name)
            ->setCreatedAt(new \DateTime($data['created_at']))
            ->setUpdatedAt(new \DateTime($data['updated_at']))
        ;
        // If there is no attributes for this rule, we simply return it
        if ($data['attribute_id'] === null) {
            return $policyRule;
        }
        $this->fetchPolicyRuleAttribute($policyRule, $data);
        // Then we fetch the remaining rows, corresponding to attributes
        while ($data = $statement->fetch()) {
            $this->fetchPolicyRuleAttribute($policyRule, $data);
        }

        return $policyRule;
    }

    /**
     * @param PolicyRule $policyRule
     * @param array      $data
     */
    public function fetchPolicyRuleAttribute(PolicyRule $policyRule, $data)
    {
        $attribute =
            (!empty($data['variable_name']))
            ?
                (new EnvironmentAttribute())
                ->setId($data['attribute_id'])
                ->setName($data['attribute_name'])
                ->setSlug($data['slug'])
                ->setVariableName($data['variable_name'])
                ->setCreatedAt(new \DateTime($data['attribute_created_at']))
                ->setUpdatedAt(new \DateTime($data['attribute_updated_at']))
            :
                (new Attribute())
                ->setId($data['attribute_id'])
                ->setName($data['attribute_name'])
                ->setSlug($data['slug'])
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
     * @param PolicyRule $policyRule
     */
    public function createPolicyRule(PolicyRule $policyRule)
    {
        $datetime = new \DateTime();
        $formattedDatetime = $datetime->format('Y-m-d H:i:s');

        $this->insert(
            'INSERT INTO abac_policy_rules(name, created_at, updated_at) '.
            'VALUES(:name, :created_at, :updated_at)', [
            'name' => $policyRule->getName(),
            'created_at' => $formattedDatetime,
            'updated_at' => $formattedDatetime,
        ]);
        $policyRule
            ->setId($this->connection->lastInsertId('abac_policy_rules'))
            ->setCreatedAt($datetime)
            ->setUpdatedAt($datetime)
        ;
    }

    /**
     * @param int                 $policyRuleId
     * @param PolicyRuleAttribute $pra
     */
    public function createPolicyRuleAttribute($policyRuleId, PolicyRuleAttribute $pra)
    {
        $this->insert(
            'INSERT INTO abac_policy_rules_attributes(policy_rule_id, attribute_id, type, comparison_type, comparison, value) '.
            'VALUES(:policy_rule_id, :attribute_id, :type, :comparison_type, :comparison, :value)', [
            'policy_rule_id' => $policyRuleId,
            'attribute_id' => $pra->getAttribute()->getId(),
            'type' => $pra->getType(),
            'comparison_type' => $pra->getComparisonType(),
            'comparison' => $pra->getComparison(),
            'value' => (!is_array($pra->getValue())) ? $pra->getValue() : serialize($pra->getValue()),
        ]);
    }
}
