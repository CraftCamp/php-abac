<?php

namespace PhpAbac\Cache\Exception;

use \Psr\Cache\CacheException;

class ExpiredCacheException extends \Exception implements CacheException {
    
}
