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
     * @param string $name
     * @param array $attributes
     * @return PolicyRule
     */
    public function create($name, $attributes) {
        $policyRule =
            (new PolicyRule())
            ->setName($name)
        ;
        
        $nbAttributes = count($attributes);
        
        for($i = 0; $i < $nbAttributes; ++$i) {
            $policyRule->addPolicyRuleAttribute(
                $this->createPolicyRuleAttribute($attributes[$i])
            );
        }
    }
    
    /**
     * @param array $data
     * @throws \InvalidArgumentException
     * @return PhpAbac\Model\PolicyRuleAttribute
     */
    public function createPolicyRuleAttribute($data) {
        if(!isset($data['comparison'])) {
            throw new \InvalidArgumentException('The attribute must have a comparison');
        }
        if(!isset($data['value'])) {
            throw new \InvalidArgumentException('The attribute must have a value');
        }
        if(!isset($data['attribute'])) {
            throw new \InvalidArgumentException('The attribute must have a key "attribute"');
        }
        return
            (new PolicyRuleAttribute())
            ->setComparison($data['comparison'])
            ->setValue('value')
            ->setAttribute(Abac::get('attribute-manager')->create(
                $data['attribute']['table'],
                $data['attribute']['column'],
                $data['attribute']['criteriaColumn']
            ))
        ;
    }
}