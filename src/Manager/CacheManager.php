<?php

namespace PhpAbac\Manager;

use Psr\Cache\CacheItemInterface;

class CacheManager
{
    /** @var string **/
    protected $defaultDriver = 'memory';
    /** @var array **/
    protected $pools;
    /** @var array **/
    protected $options;

    /**
     * @param array $options
     */
    public function __construct($options = [])
    {
        $this->options = $options;
    }

    /**
     * @param \Psr\Cache\CacheItemInterface $item
     */
    public function save(CacheItemInterface $item)
    {
        $this->getItemPool($item->getDriver())->save($item);
    }

    /**
     * @param string $key
     * @param string $driver
     * @param int $ttl
     * @return \Psr\Cache\CacheItemInterface
     */
    public function getItem($key, $driver = null, $ttl = null)
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

    /**
     *
     * @param string $driver
     * @return Psr\Cache\CacheItemPoolInterface
     */
    public function getItemPool($driver)
    {
        if (!isset($this->pools[$driver])) {
            $poolClass = 'PhpAbac\\Cache\\Pool\\' . ucfirst($driver) . 'CacheItemPool';
            $this->pools[$driver] = new $poolClass($this->options);
        }
        return $this->pools[$driver];
    }
}
