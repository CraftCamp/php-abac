<?php

namespace PhpAbac\Loader;

use Symfony\Component\Yaml\Yaml;

class YamlAbacLoader extends AbacLoader
{
    protected static $allowedExtensions = ['yml','yaml'];
    
    public function load($resource, $type = null)
    {
        return Yaml::parse(file_get_contents($resource)) + ['path' => $resource];
    }

    public function supports($resource, $type = null)
    {
        return is_string($resource) && self::supportsExtension($resource);
    }
}
