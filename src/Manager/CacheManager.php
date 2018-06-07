<?php

namespace PhpAbac\Manager;

use Psr\Cache\{
    CacheItemInterface,
    CacheItemPoolInterface
};

class CacheManager
{
    /** @var string **/
    protected $defaultDriver = 'memory';
    /** @var array **/
    protected $pools;
    /** @var array **/
    protected $options;

    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    public function save(CacheItemInterface $item)
    {
        $this->getItemPool($item->getDriver())->save($item);
    }

    public function getItem(string $key, string $driver = null, int $ttl = null): CacheItemInterface
    {
        $finalDriver = ($driver !== null) ? $driver : $this->defaultDriver;

        $pool = $this->getItemPool($finalDriver);
        $item = $pool->getItem($key);

        // In this case, the pool returned a new CacheItem
        if ($item->get() === null) {
            $item->expiresAfter($ttl);
        }
        return $item;
    }

    public function getItemPool(string $driver): CacheItemPoolInterface
    {
        if (!isset($this->pools[$driver])) {
            $poolClass = 'PhpAbac\\Cache\\Pool\\' . ucfirst($driver) . 'CacheItemPool';
            $this->pools[$driver] = new $poolClass($this->options);
        }
        return $this->pools[$driver];
    }
}
