<?php

namespace PhpAbac\Test\Cache\Pool;

use PhpAbac\Cache\Pool\TextCacheItemPool;
use PhpAbac\Cache\Item\TextCacheItem;

class TextCacheItemPoolTest extends \PHPUnit\Framework\TestCase
{
    protected $pool;

    public function setUp(): void
    {
        $this->pool = new TextCacheItemPool([
            'cache_folder' => __DIR__ . '/../../../data/cache/test'
        ]);
    }

    public function tearDown(): void
    {
        $this->pool->clear();
    }

    public function testGetItem()
    {
        $this->pool->save((new TextCacheItem('php_abac.test'))->set('test'));

        $item = $this->pool->getItem('php_abac.test');

        $this->assertInstanceOf(TextCacheItem::class, $item);
        $this->assertEquals('test', $item->get());
    }

    public function testGetUnknownItem()
    {
        $item = $this->pool->getItem('php_abac.test');

        $this->assertInstanceOf(TextCacheItem::class, $item);
        $this->assertEquals('php_abac.test', $item->getKey());
        $this->assertNull($item->get());
    }

    public function testGetItems()
    {
        $this->pool->save((new TextCacheItem('php_abac.test1'))->set('test 1'));
        $this->pool->save((new TextCacheItem('php_abac.test2'))->set('test 2'));
        $this->pool->save((new TextCacheItem('php_abac.test3'))->set('test 3'));

        $items = $this->pool->getItems([
            'php_abac.test2',
            'php_abac.test3'
        ]);
        $this->assertCount(2, $items);
        $this->assertArrayHasKey('php_abac.test2', $items);
        $this->assertInstanceOf(TextCacheItem::class, $items['php_abac.test2']);
        $this->assertEquals('test 2', $items['php_abac.test2']->get());
    }

    public function testHasItem()
    {
        $this->pool->save(new TextCacheItem('php_abac.test'));

        $this->assertFalse($this->pool->hasItem('php_abac.unknown_value'));
        $this->assertTrue($this->pool->hasItem('php_abac.test'));
    }

    public function testSave()
    {
        $this->pool->save((new TextCacheItem('php_abac.test'))->set('test'));

        $item = $this->pool->getItem('php_abac.test');

        $this->assertInstanceOf(TextCacheItem::class, $item);
        $this->assertEquals('test', $item->get());
    }

    public function testSaveDeferred()
    {
        $this->pool->saveDeferred(new TextCacheItem('php_abac.test'));

        $this->assertFalse($this->pool->hasItem('php_abac.test'));

        $this->pool->commit();

        $this->assertTrue($this->pool->hasItem('php_abac.test'));
    }

    public function testCommit()
    {
        $key = 'php_abac.test_deferred';
        $value = 'Cached value';

        $this->pool->saveDeferred((new TextCacheItem($key))->set($value));

        $this->pool->commit();

        $this->assertTrue($this->pool->hasItem($key));
        $this->assertInstanceOf(TextCacheItem::class, $this->pool->getItem($key));
        $this->assertEquals($value, $this->pool->getItem($key)->get());
    }

    public function testClear()
    {
        $this->pool->save(new TextCacheItem('php_abac.test'));

        $this->assertTrue($this->pool->clear());
        $this->assertFalse($this->pool->hasItem('php_abac.test'));
    }

    public function testDeleteItem()
    {
        $this->pool->save(new TextCacheItem('php_abac.test1'));

        $this->pool->deleteItem('php_abac.test1');

        $this->assertFalse($this->pool->hasItem('php_abac.test1'));
    }

    public function testDeleteItems()
    {
        $this->pool->save((new TextCacheItem('php_abac.test1'))->set('test 1'));
        $this->pool->save((new TextCacheItem('php_abac.test2'))->set('test 2'));
        $this->pool->save((new TextCacheItem('php_abac.test3'))->set('test 3'));
        $this->pool->deleteItems([
            'php_abac.test2',
            'php_abac.test3'
        ]);

        $items = $this->pool->getItems([
            'php_abac.test1',
            'php_abac.test2',
            'php_abac.test3'
        ]);
        $this->assertCount(1, $items);
        $this->assertArrayHasKey('php_abac.test1', $items);
    }
}
