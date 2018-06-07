<?php

namespace PhpAbac\Loader;

use Symfony\Component\Serializer\Encoder\{
    JsonDecode,
    JsonEncoder
};

class JsonAbacLoader extends AbacLoader
{
    protected static $allowedExtensions = ['json'];
    
    public function load($resource, $type = null)
    {
        return (new JsonDecode(true))->decode(file_get_contents($resource), JsonEncoder::FORMAT, [ 'json_decode_associative' => true ]) + ['path' => $resource];
    }

    public function supports($resource, $type = null)
    {
        return is_string($resource) && self::supportsExtension($resource);
    }
}
