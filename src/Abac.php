<?php

namespace PhpAbac;

use PhpAbac\Manager\AttributeManager;
use PhpAbac\Manager\PolicyRuleManager;
use PhpAbac\Manager\ConfigurationManager;
use PhpAbac\Manager\CacheManager;
use PhpAbac\Manager\ComparisonManager;

use Symfony\Component\Config\FileLocator;

use PhpAbac\Model\PolicyRuleAttribute;

class Abac {
	/** @var \PhpAbac\Manager\ConfigurationManager * */
	private $configuration;
	/** @var \PhpAbac\Manager\PolicyRuleManager * */
	private $policyRuleManager;
	/** @var \PhpAbac\Manager\AttributeManager * */
	private $attributeManager;
	/** @var \PhpAbac\Manager\CacheManager * */
	private $cacheManager;
	/** @var \PhpAbac\Manager\ComparisonManager * */
	private $comparisonManager;
	
	/**
	 * @param array  $configPaths
	 * @param array  $cacheOptions     Option for cache
	 * @param string $configPaths_root The origin folder to find $configPaths
	 */
	public function __construct( $configPaths, $cacheOptions = [], $configPaths_root = null ) {
		$this->configure( $configPaths, $configPaths_root );
		$this->attributeManager  = new AttributeManager( $this->configuration->getAttributes() );
		$this->policyRuleManager = new PolicyRuleManager( $this->attributeManager, $this->configuration->getRules() );
		$this->cacheManager      = new CacheManager( $cacheOptions );
		$this->comparisonManager = new ComparisonManager( $this->attributeManager );
	}
	
	/**
	 * @param array $configPaths
	 * @param string $configPaths_root The origin folder to find $configPaths
	 */
	public function configure( $configPaths, $configPaths_root = null ) {
		foreach($configPaths as &$configPath) {
			$configPath = $configPaths_root.$configPath;
		}
		$locator             = new FileLocator( $configPaths );
		$this->configuration = new ConfigurationManager( $locator );
		$this->configuration->setConfigPathRoot($configPaths_root);
		$this->configuration->parseConfigurationFile( $configPaths );
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
	 * @param object $resource
	 * @param array  $options
	 *
	 * @return boolean|array
	 */
	public function enforce( $ruleName, $user, $resource = null, $options = [] ) {
		// If there is dynamic attributes, we pass them to the comparison manager
		// When a comparison will be performed, the passed values will be retrieved and used
		if ( isset( $options[ 'dynamic_attributes' ] ) ) {
			$this->comparisonManager->setDynamicAttributes( $options[ 'dynamic_attributes' ] );
		}
		// Retrieve cache value for the current rule and values if cache item is valid
		if ( ( $cacheResult = isset( $options[ 'cache_result' ] ) && $options[ 'cache_result' ] === true ) === true ) {
			$cacheItem = $this->cacheManager->getItem( "$ruleName-{$user->getId()}-" . ( ( $resource !== null ) ? $resource->getId() : '' ), ( isset( $options[ 'cache_driver' ] ) ) ? $options[ 'cache_driver' ] : null, ( isset( $options[ 'cache_ttl' ] ) ) ? $options[ 'cache_ttl' ] : null );
			// We check if the cache value s valid before returning it
			if ( ( $cacheValue = $cacheItem->get() ) !== null ) {
				return $cacheValue;
			}
		}
		$policyRule = $this->policyRuleManager->getRule( $ruleName, $user, $resource );
		// For each policy rule attribute, we retrieve the attribute value and proceed configured extra data
		foreach ( $policyRule->getPolicyRuleAttributes() as $pra ) {
			$attribute = $pra->getAttribute();
			$attribute->setValue( $this->attributeManager->retrieveAttribute( $attribute, $user, $resource ) );
			if ( count( $pra->getExtraData() ) > 0 ) {
				$this->processExtraData( $pra, $user, $resource );
			}
			$this->comparisonManager->compare( $pra );
		}
		// The given result could be an array of rejected attributes or true
		// True means that the rule is correctly enforced for the given user and resource
		$result = $this->comparisonManager->getResult();
		if ( $cacheResult ) {
			$cacheItem->set( $result );
			$this->cacheManager->save( $cacheItem );
		}
		
		return $result;
	}
	
	/**
	 * @param \PhpAbac\Model\PolicyRuleAttribute $pra
	 * @param object                             $user
	 * @param object                             $resource
	 */
	public function processExtraData( PolicyRuleAttribute $pra, $user, $resource ) {
		foreach ( $pra->getExtraData() as $key => $data ) {
			switch ( $key ) {
				case 'with':
					// This data has to be removed for it will be stored elsewhere
					// in the policy rule attribute
					$pra->removeExtraData( 'with' );
					// The "with" extra data is an array of attributes, which are objects
					// Once we process it as policy rule attributes, we set it as the main policy rule attribute value
					$subPolicyRuleAttributes = [];
					$extraData               = [];
					
					foreach ( $this->policyRuleManager->processRuleAttributes( $data, $user, $resource ) as $subPolicyRuleAttribute ) {
						$subPolicyRuleAttributes[] = $subPolicyRuleAttribute;
					}
					$pra->setValue( $subPolicyRuleAttributes );
					// This data can be used in complex comparisons
					$pra->addExtraData( 'attribute', $pra->getAttribute() );
					$pra->addExtraData( 'user', $user );
					$pra->addExtraData( 'resource', $resource );
					break;
			}
		}
	}
}
