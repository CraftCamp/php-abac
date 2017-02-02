<?php

namespace PhpAbac\Test\Manager;

use PhpAbac\Manager\CacheManager;

class CacheManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PhpAbac\Manager\CacheManager **/
    protected $cacheManager;
    
    public function setUp()
    {
        $this->cacheManager = new CacheManager();
    }
    
    public function testSave()
    {
        $item = $this->cacheManager->getItemPool('memory')->getItem('php_abac.test');
        
        $item->set('Test Value');
        
        $this->cacheManager->save($item);
        
        $savedItem = $this->cacheManager->getItemPool('memory')->getItem('php_abac.test');
        
        $this->assertEquals('Test Value', $savedItem->get());
    }
    
    public function testGetItem()
    {
        $item = $this->cacheManager->getItemPool('memory')->getItem('php_abac.test');
        
        $this->assertInstanceOf('Psr\\Cache\\CacheItemInterface', $item);
        $this->assertEquals('php_abac.test', $item->getKey());
        $this->assertNull($item->get());
    }
    
    public function testGetItemPool()
    {
        $pool = $this->cacheManager->getItemPool('memory');
        $item = $pool->getItem('php_abac.test');
        $this->cacheManager->save($item);
        $items = $pool->getItems(['php_abac.test']);
        
        $this->assertInstanceOf('Psr\\Cache\\CacheItemPoolInterface', $pool);
        $this->assertCount(1, $items);
        $this->assertarrayHasKey('php_abac.test', $items);
        $this->assertEquals($item, $items['php_abac.test']);
    }
}
