<?php

namespace PhpAbac;

use PhpAbac\Manager\{
    AttributeManager,
    AttributeManagerInterface,
    CacheManager,
    CacheManagerInterface,
    ComparisonManager,
    ComparisonManagerInterface,
    PolicyRuleManager,
    PolicyRuleManagerInterface
};
use PhpAbac\Model\PolicyRule;
use PhpAbac\Model\PolicyRuleAttribute;

final class Abac
{
    /**
     * @var PolicyRuleManager|PolicyRuleManager
     */
    private $policyRuleManager;
    /**
     * @var AttributeManager|AttributeManagerInterface
     */
    private $attributeManager;
    /**
     * @var CacheManager| CacheManagerInterface
     */
    private $cacheManager;
    /**
     * @var ComparisonManager | ComparisonManagerInterface
     */
    private $comparisonManager;
    /**
     * @var array
     */
    private $errors;

    /**
     * Abac constructor.
     *
     * @param PolicyRuleManagerInterface $policyRuleManager
     * @param AttributeManagerInterface $attributeManager
     * @param ComparisonManagerInterface $comparisonManager
     * @param CacheManagerInterface $cacheManager
     */
    public function __construct(
        PolicyRuleManagerInterface $policyRuleManager,
        AttributeManagerInterface $attributeManager,
        ComparisonManagerInterface $comparisonManager,
        CacheManagerInterface $cacheManager
    ){
        $this->attributeManager = $attributeManager;
        $this->policyRuleManager = $policyRuleManager;
        $this->cacheManager = $cacheManager;
        $this->comparisonManager = $comparisonManager;
    }

    /**
     * Return true if both user and object respects all the rules conditions
     * If the objectId is null, policy rules about its attributes will be ignored
     * In case of mismatch between attributes and expected values,
     * an array with the concerned attributes slugs will be returned.
     *
     * Available options are :
     * * dynamic_attributes: array
     * * cache_result: boolean
     * * cache_ttl: integer
     * * cache_driver: string
     *
     * Available cache drivers are :
     * * memory
     *
     * @param string $ruleName
     * @param object $user
     * @param null|object $resource
     * @param array $options
     *
     * @return bool
     */
    public function enforce(string $ruleName, $user, $resource = null, array $options = []): bool
    {
        $this->errors = [];
        // If there is dynamic attributes, we pass them to the comparison manager
        // When a comparison will be performed, the passed values will be retrieved and used
        if (isset($options[ 'dynamic_attributes' ])) {
            $this->comparisonManager->setDynamicAttributes($options[ 'dynamic_attributes' ]);
        }
        // Retrieve cache value for the current rule and values if cache item is valid
        $cacheResult = (isset($options[ 'cache_result' ]) && $options[ 'cache_result' ] === true);

        if ($cacheResult === true) {
            $cacheItem = $this->cacheManager->getItem(
                "$ruleName-{$user->getId()}-" . (($resource !== null) ? $resource->getId() : ''),
                (isset($options[ 'cache_driver' ])) ? $options[ 'cache_driver' ] : null,
                (isset($options[ 'cache_ttl' ])) ? $options[ 'cache_ttl' ] : null
            );
            // We check if the cache value s valid before returning it
            if (($cacheValue = $cacheItem->get()) !== null) {
                return $cacheValue;
            }
        }
        $policyRules = $this->policyRuleManager->getRule($ruleName, $user, $resource);
        
        foreach ($policyRules as $policyRule) {
            // For each policy rule attribute, we retrieve the attribute value and proceed configured extra data
            /**
             * @var PolicyRule $policyRule
             */
            foreach ($policyRule->getPolicyRuleAttributes() as $pra) {
                /** @var PolicyRuleAttribute $pra */
                $attribute = $pra->getAttribute();
                
                $getter_params = $this->prepareGetterParams($pra->getGetterParams(), $user, $resource);
                $attribute->setValue(
                    $this->attributeManager->retrieveAttribute($attribute, $user, $resource, $getter_params)
                );
                if (count($pra->getExtraData()) > 0) {
                    $this->processExtraData($pra, $user, $resource);
                }
                $this->comparisonManager->compare($pra);
            }
            // The given result could be an array of rejected attributes or true
            // True means that the rule is correctly enforced for the given user and resource
            $this->errors = $this->comparisonManager->getResult();
            if (count($this->errors) === 0) {
                break;
            }
        }
        if ($cacheResult) {
            $cacheItem->set((count($this->errors) > 0) ? $this->errors : true);
            $this->cacheManager->save($cacheItem);
        }
        return count($this->errors) === 0;
    }
    
    public function getErrors(): array
    {
        return $this->errors;
    }
    
    /**
     * Function to prepare Getter Params when getter require parameters
     *      ( this parameters must be specified in configuration file)
     *
     * @param $getter_params
     * @param $user
     * @param $resource
     *
     * @return array
     */
    private function prepareGetterParams($getter_params, $user, $resource)
    {
        if (empty($getter_params)) {
            return [];
        }
        $values = [];
        foreach ($getter_params as $getter_name=>$params) {
            foreach ($params as $param) {
                if ('@' !== $param[ 'param_name' ][ 0 ]) {
                    $values[$getter_name][] = $param[ 'param_value' ];
                } else {
                    $values[$getter_name][] = $this->attributeManager
                        ->retrieveAttribute(
                            $this->attributeManager->getAttribute($param[ 'param_value' ]),
                            $user,
                            $resource
                    );
                }
            }
        }
        return $values;
    }
    
    private function processExtraData(PolicyRuleAttribute $pra, $user, $resource)
    {
        foreach ($pra->getExtraData() as $key => $data) {
            switch ($key) {
                case 'with':
                    // This data has to be removed for it will be stored elsewhere
                    // in the policy rule attribute
                    $pra->removeExtraData('with');
                    // The "with" extra data is an array of attributes, which are objects
                    // Once we process it as policy rule attributes, we set it as the main policy rule attribute value
                    $subPolicyRuleAttributes = [];
                    $subs = $this->policyRuleManager->processRuleAttributes($data, $user, $resource);
                    foreach ($subs as $subPolicyRuleAttribute) {
                        $subPolicyRuleAttributes[] = $subPolicyRuleAttribute;
                    }
                    $pra->setValue($subPolicyRuleAttributes);
                    // This data can be used in complex comparisons
                    $pra->addExtraData('attribute', $pra->getAttribute());
                    $pra->addExtraData('user', $user);
                    $pra->addExtraData('resource', $resource);
                    break;
            }
        }
    }

}
