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

/**
 * Class AbacFactory
 *
 * @package PhpAbac
 */
final class AbacFactory
{
    /**
     * @var ConfigurationInterface
     */
    protected static $configuration;
    /**
     * @var PolicyRuleManagerInterface *
     */
    protected static $policyRuleManager;
    /**
     * @var AttributeManagerInterface *
     */
    protected static $attributeManager;
    /**
     * @var CacheManagerInterface *
     */
    protected static $cacheManager;
    /**
     * @var ComparisonManagerInterface *
     */
    protected static $comparisonManager;

    /**
     * @param ConfigurationInterface $configuration
     */
    public static function setConfiguration(ConfigurationInterface $configuration)
    {
        self::$configuration = $configuration;
    }

    /**
     * @param PolicyRuleManagerInterface $policyRuleManager
     */
    public static function setPolicyRuleManager(PolicyRuleManagerInterface $policyRuleManager)
    {
        self::$policyRuleManager = $policyRuleManager;
    }

    /**
     * @param AttributeManagerInterface $attributeManager
     */
    public static function setAttributeManager(AttributeManagerInterface $attributeManager)
    {
        self::$attributeManager = $attributeManager;
    }

    /**
     * @param CacheManagerInterface $cacheManager
     */
    public static function setCacheManager(CacheManagerInterface $cacheManager)
    {
        self::$cacheManager = $cacheManager;
    }

    /**
     * @param ComparisonManagerInterface $comparisonManager
     */
    public static function setComparisonManager(ComparisonManagerInterface $comparisonManager)
    {
        self::$comparisonManager = $comparisonManager;
    }

    /**
     * @param array $configurationFiles
     * @param string|null $configDir
     * @param array $attributeOptions
     * @param array $cacheOptions
     *
     * @return Abac
     */
    public static function getAbac(
        array $configurationFiles,
        string $configDir = null,
        array $attributeOptions = [],
        array $cacheOptions = [])
    {
        $configuration =
            (self::$configuration !== null)
            ? self::$configuration
            : new Configuration($configurationFiles, $configDir);

        $attributeManager =
            (self::$attributeManager !== null)
            ? self::$attributeManager
            : new AttributeManager($configuration, $attributeOptions);

        $policyRuleManager =
            (self::$policyRuleManager !== null)
            ? self::$policyRuleManager
            : new PolicyRuleManager($configuration, $attributeManager);

        $comparisonManager =
            (self::$comparisonManager !== null)
            ? self::$comparisonManager
            : new ComparisonManager($attributeManager);

        $cacheManager =
            (self::$cacheManager !== null)
            ? self::$cacheManager
            : new CacheManager($cacheOptions);
        
        return new Abac($policyRuleManager, $attributeManager, $comparisonManager, $cacheManager);
    }

    public static function getRules()
    {
        return self::$configuration != null ? self::$configuration->getRules() : [];
    }
}