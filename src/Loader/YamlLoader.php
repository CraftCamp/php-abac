<?php

namespace PhpAbac\Loader;

use Symfony\Component\Yaml\Yaml;

use Symfony\Component\Config\Loader\FileLoader;

class YamlLoader extends FileLoader
{
    /**
     * @param string $filename
     * @param null $type
     *
     * @return mixed
     */
    public function load($filename, $type = null)
    {
        return Yaml::parse(file_get_contents($filename));
    }

    /**
     * @param string $filename
     * @param null $type
     *
     * @return bool
     */
    public function supports($filename, $type = null): bool
    {
        return in_array(pathinfo($filename, PATHINFO_EXTENSION), ['yml', 'yaml']);
    }
}
