<?php

namespace PhpAbac\Cache\Pool;

use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\CacheItemInterface;

use PhpAbac\Cache\Item\TextCacheItem;

class TextCacheItemPool implements CacheItemPoolInterface {
    /** @var array **/
    protected $deferredItems;
    /** @var string **/
    protected $cacheFolder;

    /**
     * @param array $options
     */
    public function __construct($options = []) {
        $this->configure($options);
    }

    /**
     * @param array $options
     */
    protected function configure($options) {
        $this->cacheFolder =
            (isset($options['cache_folder']))
            ? "{$options['cache_folder']}/text"
            : __DIR__ . '/../../../data/cache/text'
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteItem($key) {
        if(is_file("{$this->cacheFolder}/$key.txt")) {
            unlink("{$this->cacheFolder}/$key.txt");
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
        $data = "{$item->get()};{$item->getExpirationDate()->format('Y-m-d H:i:s')}";

        file_put_contents("{$this->cacheFolder}/{$item->getKey()}.txt", $data);

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
            $this->save($item);
            unset($this->deferredItems[$key]);
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function hasItem($key) {
        return is_file("{$this->cacheFolder}/{$key}.txt");
    }

    /**
     * {@inheritdoc}
     */
    public function getItem($key) {
        $item = new TextCacheItem($key);
        if(!$this->hasItem($key)) {
            return $item;
        }
        $data = explode(';',file_get_contents("{$this->cacheFolder}/{$key}.txt"));
        return $item
            ->set($data[0])
            ->expiresAt((new \DateTime($data[1])))
        ;
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
        $items = glob("{$this->cacheFolder}/*.txt"); // get all file names
        foreach($items as $item){ // iterate files
          if(is_file($item))
            unlink($item); // delete file
        }
        $this->deferredItems = [];
        return true;
    }
}
