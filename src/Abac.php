<?php

namespace PhpAbac;

use PhpAbac\Manager\AttributeManager;
use PhpAbac\Manager\PolicyRuleManager;
use PhpAbac\Manager\CacheManager;

class Abac
{
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
        self::set('cache-manager', new CacheManager(), true);
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
     * @param integer $userId
     * @param integer $objectId
     * @param array $options
     * @return boolean|array
     */
    public function enforce($ruleName, $userId, $objectId = null, $options = []) {
        $attributeManager = self::get('attribute-manager');
        $cacheManager = self::get('cache-manager');
        
        if(($cacheResult = isset($options['cache_result']) && $options['cache_result'] === true) === true) {
            $cacheItem = $cacheManager->getItem(
                "$ruleName-$userId-$objectId",
                (isset($options['cache_driver'])) ? $options['cache_driver'] : null,
                (isset($options['cache_ttl'])) ? $options['cache_ttl'] : null
            );
            if(($cacheValue = $cacheItem->get()) !== null) {
                return $cacheValue;
            }
        }
        $policyRule = self::get('policy-rule-manager')->getRuleByName($ruleName);
        $rejectedAttributes = [];

        foreach ($policyRule->getPolicyRuleAttributes() as $pra) {
            $attribute = $pra->getAttribute();
            $attributeManager->retrieveAttribute($attribute, $pra->getType(), $userId, $objectId);

            $comparisonClass = 'PhpAbac\\Comparison\\'.ucfirst($pra->getComparisonType()).'Comparison';
            $comparison = new $comparisonClass();
            $dynamicAttributes = (isset($options['dynamic_attributes'])) ? $options['dynamic_attributes'] : [];
            $value =
                ($pra->getValue() === 'dynamic')
                ? $attributeManager->getDynamicAttribute($attribute->getSlug(), $dynamicAttributes)
                : $pra->getValue()
            ;
            if ($comparison->{$pra->getComparison()}($value, $attribute->getValue()) !== true) {
                $rejectedAttributes[] = $attribute->getSlug();
            }
        }
        $result = (count($rejectedAttributes) === 0) ? : $rejectedAttributes;
        if($cacheResult) {
            $cacheItem->set($result);
            $cacheManager->save($cacheItem);
        }
        return $result;
    }

    public static function clearContainer()
    {
        self::$container = null;
    }

    /**
     * @param string $serviceName
     * @param mixed  $service
     * @param bool   $force
     *
     * @throws \InvalidArgumentException
     */
    public static function set($serviceName, $service, $force = false)
    {
        if (self::has($serviceName) && $force === false) {
            throw new \InvalidArgumentException(
                "The service $serviceName is already set in PhpAbac container. ".
                'Please set $force parameter to true if you want to replace the set service'
            );
        }
        self::$container[$serviceName] = $service;
    }

    /**
     * @param string $serviceName
     *
     * @return bool
     */
    public static function has($serviceName)
    {
        return isset(self::$container[$serviceName]);
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return mixed
     */
    public static function get($serviceName)
    {
        if (!self::has($serviceName)) {
            throw new \InvalidArgumentException("The PhpAbac container has no service named $serviceName");
        }

        return self::$container[$serviceName];
    }
}
