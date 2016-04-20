<?php

namespace PhpAbac\Cache\Pool;

use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\CacheItemInterface;

use PhpAbac\Cache\Item\MemoryCacheItem;

class MemoryCacheItemPool implements CacheItemPoolInterface {
    /** @var array **/
    protected $items;
    /** @var array **/
    protected $deferredItems;
    /**
     * {@inheritdoc}
     */
    public function deleteItem($key) {
        if(isset($this->items[$key])) {
            unset($this->items[$key]);
        }
        if(isset($this->deferredItems[$key])) {
            unset($this->deferredItems[$key]);
        }
        return true;
    }
    
    /**
     * {@inheritdoc}
     */
    public function deleteItems(array $keys) {
        foreach($keys as $key) {
            if(!$this->deleteItem($key)) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * {@inheritdoc}
     */
    public function save(CacheItemInterface $item) {
        $this->items[$item->getKey()] = $item;
        
        return true;
    }
    
    /**
     * {@inheritdoc}
     */
    public function saveDeferred(CacheItemInterface $item) {
        $this->deferredItems[$item->getKey()] = $item;
        
        return true;
    }
    
    /**
     * {@inheritdoc}
     */
    public function commit() {
        foreach($this->deferredItems as $key => $item) {
            $this->items[$key] = $item;
            unset($this->deferredItems[$key]);
        }
        return true;
    }
    
    /**
     * {@inheritdoc}
     */
    public function hasItem($key) {
        return isset($this->items[$key]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getItem($key) {
        if(!$this->hasItem($key)) {
            return new MemoryCacheItem($key);
        }
        return $this->items[$key];
    }
    
    /**
     * {@inheritdoc}
     */
    public function getItems(array $keys = array()) {
        $items = [];
        foreach($keys as $key) {
            if($this->hasItem($key)) {
                $items[$key] = $this->getItem($key);
            }
        }
        return $items;
    }
    
    /**
     * {@inheritdoc}
     */
    public function clear() {
        $this->items = [];
        $this->deferredItems = [];
        return true;
    }
}