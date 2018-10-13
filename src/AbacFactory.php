<?php

namespace PhpAbac;

use PhpAbac\Configuration\ConfigurationInterface;
use PhpAbac\Configuration\Configuration;

use PhpAbac\Manager\PolicyRuleManager;
use PhpAbac\Manager\PolicyRuleManagerInterface;
use PhpAbac\Manager\AttributeManager;
use PhpAbac\Manager\AttributeManagerInterface;
use PhpAbac\Manager\CacheManager;
use PhpAbac\Manager\CacheManagerInterface;
use PhpAbac\Manager\ComparisonManager;
use PhpAbac\Manager\ComparisonManagerInterface;


final class AbacFactory
{
    /** @var ConfigurationInterface **/
    protected static $configuration;
    /** @var PolicyRuleManagerInterface **/
    protected static $policyRuleManager;
    /** @var AttributeManagerInterface **/
    protected static $attributeManager;
    /** @var CacheManagerInterface **/
    protected static $cacheManager;
    /** @var ComparisonManagerInterface **/
    protected static $comparisonManager;
    
    public static function setConfiguration(ConfigurationInterface $configuration)
    {
        self::$configuration = $configuration;
    }
    
    public static function setPolicyRuleManager(PolicyRuleManagerInterface $policyRuleManager)
    {
        self::$policyRuleManager = $policyRuleManager;
    }
    
    public static function setAttributeManager(AttributeManagerInterface $attributeManager)
    {
        self::$attributeManager = $attributeManager;
    }
    
    public static function setCacheManager(CacheManagerInterface $cacheManager)
    {
        self::$cacheManager = $cacheManager;
    }
    
    public static function setComparisonManager(ComparisonManagerInterface $comparisonManager)
    {
        self::$comparisonManager = $comparisonManager;
    }
    
    public static function getAbac(array $configurationFiles, string $configDir = null, array $attributeOptions = [], array $cacheOptions = [])
    {
        $configuration = (self::$configuration !== null) ? self::$configuration : new Configuration($configurationFiles, $configDir);
        $attributeManager = (self::$attributeManager !== null) ? self::$attributeManager : new AttributeManager($configuration, $attributeOptions);
        $policyRuleManager = (self::$policyRuleManager !== null) ? self::$policyRuleManager : new PolicyRuleManager($configuration, $attributeManager);
        $comparisonManager = (self::$comparisonManager !== null) ? self::$comparisonManager : new ComparisonManager($attributeManager);
        $cacheManager = (self::$cacheManager !== null) ? self::$cacheManager : new CacheManager($cacheOptions);
        
        return new Abac($policyRuleManager, $attributeManager, $comparisonManager, $cacheManager);
    }
}