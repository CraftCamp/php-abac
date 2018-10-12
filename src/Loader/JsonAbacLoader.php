<?php

namespace PhpAbac\Loader;

use Symfony\Component\Config\Loader\FileLoader;

class JsonAbacLoader extends FileLoader
{
    public function load($resource, $type = null)
    {
        return json_decode(file_get_contents($resource), true);
    }

    public function supports($resource, $type = null): bool
    {
        return pathinfo($resource, PATHINFO_EXTENSION) === 'json';
    }
}
