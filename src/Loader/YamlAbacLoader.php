<?php

namespace PhpAbac\Loader;

use Symfony\Component\Yaml\Yaml;

class YamlAbacLoader extends AbacLoader
{
    protected static $_EXTENSION_ALLOWED_A = ['yml','yaml'];
    
    public function load($resource, $type = null)
    {
        //    	$path_to_load = $this->locator->locate($resource);
        $path_to_load = $resource;
         
        return Yaml::parse(file_get_contents($path_to_load)) + ['path' => $path_to_load];
    }

    public function supports($resource, $type = null)
    {
        return is_string($resource) && self::supportsExtension($resource);
    }
}
