<?php

namespace PhpAbac\Cache\Item;

use Psr\Cache\CacheItemInterface;

use PhpAbac\Cache\Exception\ExpiredCacheException;

class TextCacheItem implements CacheItemInterface
{
    /** @var string **/
    protected $key;
    /** @var mixed **/
    protected $value;
    /** @var int **/
    protected $defaultLifetime = 3600;
    /** @var \DateTime **/
    protected $expiresAt;
    /** @var string **/
    protected $driver = 'text';

    public function __construct(string $key, int $ttl = null)
    {
        $this->key = $key;
        $this->expiresAfter($ttl);
    }

    public function set($value): TextCacheItem
    {
        $this->value = $value;

        return $this;
    }

    public function isHit(): bool
    {
        return $this->expiresAt >= new \DateTime();
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function get()
    {
        if (!$this->isHit()) {
            throw new ExpiredCacheException('Cache item is expired');
        }
        return $this->value;
    }

    public function expiresAfter($time): TextCacheItem
    {
        $lifetime = ($time !== null) ? $time : $this->defaultLifetime;

        $this->expiresAt = (new \DateTime())->setTimestamp(time() + $lifetime);

        return $this;
    }

    public function expiresAt($expiration): TextCacheItem
    {
        $this->expiresAt =
            ($expiration === null)
            ? (new \DateTime())->setTimestamp(time() + $this->defaultLifetime)
            : $expiration
        ;
        return $this;
    }

    public function getExpirationDate(): \DateTime
    {
        return $this->expiresAt;
    }

    public function getDriver(): string
    {
        return $this->driver;
    }
}
