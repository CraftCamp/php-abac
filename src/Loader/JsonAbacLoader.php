<?php

namespace PhpAbac\Loader;

use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class JsonAbacLoader extends AbacLoader
{
	protected static $_EXTENSION_ALLOWED_A = ['json'];
	
    public function load($resource, $type = null)
    {
    	return (new JsonDecode(true))->decode(file_get_contents($resource),JsonEncoder::FORMAT,[ 'json_decode_associative' => true ] );
    }

    public function supports($resource, $type = null)
    {
        return is_string($resource) && self::supportsExtension($resource);
    }
}