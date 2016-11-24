<?php
/**
 * Projet :  php-abac.
 * User: mvedie
 * Date: 24/11/2016
 * Time: 18:51
 */

namespace PhpAbac\Loader;

use Symfony\Component\Config\Loader\FileLoader;

abstract class AbacLoader extends FileLoader {
	/**
	 * Must be overrided to contains an array of allowed extension
	 */
	const EXTENSION_ALLOWED_A = [];
	
	/**
	 * Method to load a resource and return an array that contains decoded data of the resource
	 *
	 * @param string $resource The path of the resource to load
	 * @param null   $type     ??
	 *
	 * @return string
	 */
	abstract public function load( $resource, $type = null );
	
	/**
	 * Method to check if a resource ( by his path ) is supported by the loader
	 *
	 * @param string $resource The path of the resource to check
	 * @param null   $type     ??
	 *
	 * @return boolean Return true if the resource is supported by the loader
	 */
	abstract public function supports( $resource, $type = null );
	
	/**
	 * Method to check if a resource is supported by the loader
	 * This method check only extension file
	 *
	 * @param $resource
	 *
	 * @return boolean Return true if the extension of the ressource is supported by the loader
	 */
	public static final function supportsExtension( $resource ) {
		return in_array( pathinfo( $resource, PATHINFO_EXTENSION ), static::EXTENSION_ALLOWED_A );
	}
}

?>
