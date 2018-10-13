<?php

namespace PhpAbac\Manager;

use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

interface CacheManagerInterface
{
    public function save(CacheItemInterface $item);
    
    public function getItem(string $key, string $driver = null, int $ttl = null): CacheItemInterface;
    
    public function getItemPool(string $driver): CacheItemPoolInterface;
}