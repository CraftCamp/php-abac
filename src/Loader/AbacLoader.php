<?php

namespace PhpAbac\Loader;

use Symfony\Component\Config\Loader\FileLoader;

abstract class AbacLoader extends FileLoader
{
    /**
     * @var array
     *      Must be defined in child classes to contains an array of allowed extension
     */
    protected static $allowedExtensions = [];
    /**
     * @var ConfigurationManager
     *      The configuration manage instanciator and user of this AbacLoader Instance
     */
    protected $configurationManger;
    
    /**
     * Method to load a resource and return an array that contains decoded data of the resource
     *
     * @param string $resource The path of the resource to load
     * @param null   $type     ??
     *
     * @return string
     */
    abstract public function load($resource, $type = null);
    
    /**
     * Method to check if a resource is supported by the loader
     * This method check only extension file
     *
     * @param $resource
     *
     * @return boolean Return true if the extension of the ressource is supported by the loader
     */
    final public static function supportsExtension($resource): bool
    {
        return in_array(pathinfo($resource, PATHINFO_EXTENSION), self::getAllowedExtensions());
    }

    /**
     * Method to return allowed extension for file to load with the loader
     * @return mixed
     */
    final private static function getAllowedExtensions()
    {
        return static::$allowedExtensions;
    }
}
