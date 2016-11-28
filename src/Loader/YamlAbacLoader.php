<?php

namespace PhpAbac\Loader;

use Symfony\Component\Yaml\Yaml;

class YamlAbacLoader extends AbacLoader
{
	protected static $_EXTENSION_ALLOWED_A = ['yml','yaml'];
	
    public function load($resource, $type = null)
    {
        return Yaml::parse(file_get_contents($this->locator->locate($resource)));
    }

    public function supports($resource, $type = null)
    {
        return is_string($resource) && self::supportsExtension($resource);
    }
}