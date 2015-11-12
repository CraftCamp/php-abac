<?php

namespace PhpAbac;

use PhpAbac\Manager\AttributeManager;
use PhpAbac\Manager\PolicyRuleManager;

class Abac {
    /** @var array **/
    private static $container;
    
    /**
     * @param \PDO $connection
     */
    public function __construct(\PDO $connection)
    {
        // Set the main managers
        self::set('pdo-connection', $connection, true);
        self::set('policy-rule-manager', new PolicyRuleManager(), true);
        self::set('attribute-manager', new AttributeManager(), true);
    }
    
    /**
     * Return true if both user and object respects all the rules conditions
     * If the objectId is null, policy rules about its attributes will be ignored
     * In case of mismatch between attributes and expected values,
     * an array with the concerned attributes slugs will be returned
     * 
     * @param string $ruleName
     * @param integer $userId
     * @param integer $objectId
     * @return boolean|array
     */
    public function enforce($ruleName, $userId, $objectId = null, $dynamicAttributes = []) {
        $attributeManager = self::get('attribute-manager');
        
        $policyRule = self::get('policy-rule-manager')->getRuleByName($ruleName);
        $rejectedAttributes = [];
        
        foreach($policyRule->getPolicyRuleAttributes() as $pra) {
            $attribute = $pra->getAttribute();
            $attributeManager->retrieveAttribute($attribute, $pra->getType(), $userId, $objectId);
            
            $comparisonClass = 'PhpAbac\\Comparison\\'. $pra->getComparisonType() . 'Comparison';
            $comparison = new $comparisonClass();
            $value =
                ($pra->getValue() === 'dynamic')
                ? $attributeManager->getDynamicAttribute($attribute->getSlug(), $dynamicAttributes)
                : $pra->getValue()
            ;
            if($comparison->{$pra->getComparison()}($value, $attribute->getValue()) !== true) {
                $rejectedAttributes[] = $attribute->getSlug();
            }
        }
        return (count($rejectedAttributes) === 0) ? : $rejectedAttributes;
    }
    
    public static function clearContainer() {
        self::$container = null;
    }
    
    /**
     * @param string $serviceName
     * @param mixed $service
     * @param boolean $force
     * @throws \InvalidArgumentException
     */
    public static function set($serviceName, $service, $force = false) {
        if(self::has($serviceName) && $force === false) {
            throw new \InvalidArgumentException(
                "The service $serviceName is already set in PhpAbac container. ".
                'Please set $force parameter to true if you want to replace the set service'
            );
        }
        self::$container[$serviceName] = $service;
    }
    
    /**
     * @param string $serviceName
     * @return boolean
     */
    public static function has($serviceName) {
        return isset(self::$container[$serviceName]);
    }
    
    /**
     * @throws \InvalidArgumentException
     * @return mixed
     */
    public static function get($serviceName) {
        if(!self::has($serviceName)) {
            throw new \InvalidArgumentException("The PhpAbac container has no service named $serviceName");
        }
        return self::$container[$serviceName];
    }
}