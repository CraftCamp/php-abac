<?php

namespace PhpAbac\Loader;

use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class JsonAbacLoader extends AbacLoader
{
	protected static $_EXTENSION_ALLOWED_A = ['json'];
	
    public function load($resource, $type = null)
    {
//    	$path_to_load = $this->locator->locate($resource);
    	$path_to_load = $resource;
    	
    	return (new JsonDecode(true))->decode(file_get_contents($path_to_load),JsonEncoder::FORMAT,[ 'json_decode_associative' => true ] ) + ['path' => $path_to_load];
    }

    public function supports($resource, $type = null)
    {
        return is_string($resource) && self::supportsExtension($resource);
    }
}