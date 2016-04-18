<?php

namespace PhpAbac\Cache\Item;

use Psr\Cache\CacheItemInterface;

use PhpAbac\Cache\Exception\ExpiredCacheException;

class MemoryCacheItem implements CacheItemInterface {
    /** @var string **/
    protected $key;
    /** @var mixed **/
    protected $value;
    /** @var int **/
    protected $defaultLifetime = 3600;
    /** @var \DateTime **/
    protected $expiresAt;
    
    /**
     * @param string $key
     */
    public function __construct($key, $ttl = null) {
        $this->key = $key;
        $this->expiresAfter($ttl);
    }
    
    /**
     * {@inheritdoc}
     */
    public function set($value) {
        $this->value = $value;
        
        return $this;
    }
    
    /**
     * {@inheritdoc}
     */
    public function isHit() {
        return $this->expiresAt >= new \DateTime();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getKey() {
        return $this->key;
    }
    
    /**
     * {@inheritdoc}
     */
    public function get() {
        if(!$this->isHit()) {
            throw new ExpiredCacheException('Cache item is expired');
        }
        return $this->value;
    }
    
    /**
     * {@inheritdoc}
     */
    public function expiresAfter($time) {
        $lifetime = ($time !== null) ? $time : $this->defaultLifetime;
        
        $this->expiresAt = (new \DateTime())->setTimestamp(time() + $lifetime);
        
        return $this;
    }
    
    /**
     * {@inheritdoc}
     */
    public function expiresAt($expiration) {
        $this->expiresAt =
            ($expiration === null)
            ? (new \DateTime())->setTimestamp(time() + $this->defaultLifetime)
            : $expiration
        ;
        return $this;
    }
    
    /**
     * @return \DateTime
     */
    public function getExpirationDate() {
        return $this->expiresAt;
    }
}