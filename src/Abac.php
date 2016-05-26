<?php

namespace PhpAbac;

use PhpAbac\Manager\AttributeManager;
use PhpAbac\Manager\PolicyRuleManager;
use PhpAbac\Manager\ConfigurationManager;
use PhpAbac\Manager\CacheManager;

use Symfony\Component\Config\FileLocator;

class Abac
{
    /** @var \PhpAbac\Manager\ConfigurationManager **/
    private $configuration;
    /** @var \PhpAbac\Manager\PolicyRuleManager **/
    private $policyRuleManager;
    /** @var \PhpAbac\Manager\AttributeManager **/
    private $attributeManager;
    /** @var \PhpAbac\Manager\CacheManager **/
    private $cacheManager;

    public function __construct()
    {
        $this->configure();
        $this->attributeManager = new AttributeManager($this->configuration->getAttributes());
        $this->policyRuleManager = new PolicyRuleManager($this->attributeManager, $this->configuration->getRules());
        $this->cacheManager = new CacheManager();
    }
    
    public function configure() {
        $locator = new FileLocator([__DIR__.'/../']);
        $this->configuration = new ConfigurationManager($locator);
        $this->configuration->parseConfigurationFile();
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
     * @param object $object
     * @param array $options
     * @return boolean|array
     */
    public function enforce($ruleName, $user, $object = null, $options = []) {
        // Retrieve cache value for the current rule and values if cache item is valid
        if(($cacheResult = isset($options['cache_result']) && $options['cache_result'] === true) === true) {
            $cacheItem = $this->cacheManager->getItem(
                "$ruleName-{$user->getId()}-" . (($object !== null) ? $object->getId() : ''),
                (isset($options['cache_driver'])) ? $options['cache_driver'] : null,
                (isset($options['cache_ttl'])) ? $options['cache_ttl'] : null
            );
            // We check if the cache value s valid before returning it
            if(($cacheValue = $cacheItem->get()) !== null) {
                return $cacheValue;
            }
        }
        $policyRule = $this->policyRuleManager->getRule($ruleName);
        $rejectedAttributes = [];

        
        foreach ($policyRule->getPolicyRuleAttributes() as $pra) {
            $attribute = $pra->getAttribute();
            $attribute->setValue($this->attributeManager->retrieveAttribute($attribute, $user, $object));
            $comparisonClass = 'PhpAbac\\Comparison\\'.ucfirst($pra->getComparisonType()).'Comparison';
            $comparison = new $comparisonClass();
            $dynamicAttributes = (isset($options['dynamic_attributes'])) ? $options['dynamic_attributes'] : [];
            $value =
                ($pra->getValue() === 'dynamic')
                ? $this->attributeManager->getDynamicAttribute($attribute->getSlug(), $dynamicAttributes)
                : $pra->getValue()
            ;
            if ($comparison->{$pra->getComparison()}($value, $attribute->getValue()) !== true) {
                $rejectedAttributes[] = $attribute->getSlug();
            }
        }
        $result = (count($rejectedAttributes) === 0) ? : $rejectedAttributes;
        if($cacheResult) {
            $cacheItem->set($result);
            $this->cacheManager->save($cacheItem);
        }
        return $result;
    }
}
