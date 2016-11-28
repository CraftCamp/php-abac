<?php

namespace PhpAbac\Manager;

use PhpAbac\Loader\AbacLoader;
use PhpAbac\Loader\JsonAbacLoader;
use PhpAbac\Loader\YamlAbacLoader;

use Symfony\Component\Config\FileLocatorInterface;

class ConfigurationManager {
	/** @var FileLocatorInterface * */
	protected $locator;
	/** @var AbacLoader[] * */
	protected $loaders;
	/** @var array * */
	protected $rules;
	/** @var array * */
	protected $attributes;
	
//	protected $config_path_route;
	
	/** @var array List of File Already Loader */
	protected $config_files_loaded;
	
	/**
	 * @param FileLocatorInterface $locator
	 * @param string|array         $format A format or an array of format
	 */
	public function __construct( FileLocatorInterface $locator, $format = [
		'yaml',
		'json',
	] ) {
		$this->locator             = $locator;
		$this->attributes          = [];
		$this->rules               = [];
		$this->config_files_loaded = [];
		if ( in_array( 'yaml', $format ) ) {
			$this->loaders[ 'yaml' ] = new YamlAbacLoader( $locator, $this );
		}
		if ( in_array( 'json', $format ) ) {
			$this->loaders[ 'json' ] = new JsonAbacLoader( $locator, $this );
		}
		
	}
	
	public function setConfigPathRoot($configPaths_root = null) {
//		$this->config_path_route = $configPaths_root;
		foreach($this->loaders as $loader) {
			$loader->setCurrentDir($configPaths_root);
		}
	}
		
	/**
	 * @param array $configurationFiles
	 */
	public function parseConfigurationFile( $configurationFiles ) {
		foreach ( $configurationFiles as $configurationFile ) {
			$config = $this->getLoader( $configurationFile )->import( $configurationFile, pathinfo( $configurationFile, PATHINFO_EXTENSION ) );
			
			if (in_array($config['path'],$this->config_files_loaded)) {
				continue;
			}
			
			$this->config_files_loaded[] = $config['path'];
			
			if (isset($config['@import'])) {
				$this->parseConfigurationFile($config['@import']);
				unset($config['@import']);
			}
			
			if ( isset( $config[ 'attributes' ] ) ) {
				$this->attributes = array_merge( $this->attributes, $config[ 'attributes' ] );
			}
			if ( isset( $config[ 'rules' ] ) ) {
				$this->rules = array_merge( $this->rules, $config[ 'rules' ] );
			}
		}
	}
	
	
	
	/**
	 * Function to retrieve the good loader for the configuration file
	 *
	 * @param $configurationFile
	 *
	 * @return AbacLoader
	 *
	 * @throws \Exception
	 */
	private function getLoader( $configurationFile ) {
		
		foreach ( $this->loaders as $AbacLoader ) {
			if ( $AbacLoader::supportsExtension( $configurationFile ) ) {
				return $AbacLoader;
			}
		}
		throw new \Exception( 'Loader not found for the file ' . $configurationFile );
	}
	
	/**
	 * @return array
	 */
	public function getAttributes() {
		return $this->attributes;
	}
	
	/**
	 * @return array
	 */
	public function getRules() {
		return $this->rules;
	}
}