<?php

namespace PhpAbac\Loader;

use Symfony\Component\Config\Loader\FileLoader;

class JsonLoader extends FileLoader
{
    /**
     * @param string $filename
     * @param null $type
     *
     * @return mixed
     */
    public function load($filename, $type = null)
    {
        return json_decode(file_get_contents($filename), true);
    }

    /**
     * @param string $filename
     * @param null $type
     *
     * @return bool
     */
    public function supports($filename, $type = null): bool
    {
        return pathinfo($filename, PATHINFO_EXTENSION) === 'json';
    }
}
