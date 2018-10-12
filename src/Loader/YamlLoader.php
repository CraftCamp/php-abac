<?php

namespace PhpAbac\Loader;

use Symfony\Component\Yaml\Yaml;

use Symfony\Component\Config\Loader\FileLoader;

class YamlLoader extends FileLoader
{
    public function load($resource, $type = null)
    {
        return Yaml::parse(file_get_contents($resource));
    }

    public function supports($resource, $type = null): bool
    {
        return in_array(pathinfo($resource, PATHINFO_EXTENSION), ['yml','yaml']);
    }
}
