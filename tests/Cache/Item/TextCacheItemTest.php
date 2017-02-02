<?php

namespace PhpAbac\Test\Cache\Item;

use PhpAbac\Cache\Item\TextCacheItem;

class TextCacheItemTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PhpAbac\Cache\Item\TextCacheItem **/
    protected $item;

    public function setUp()
    {
        $this->item = new TextCacheItem('php_abac.test');
    }

    public function testSet()
    {
        $this->item->set('test');

        $this->assertEquals('test', $this->item->get());
    }

    public function testIsHit()
    {
        $this->assertTrue($this->item->isHit());
    }

    public function testIsHitWithMissItem()
    {
        $this->item->expiresAt((new \DateTime())->setTimestamp(time() - 100));

        $this->assertFalse($this->item->isHit());
    }

    public function testGetKey()
    {
        $this->assertEquals('php_abac.test', $this->item->getKey());
    }

    public function testGet()
    {
        $this->item->set('test');

        $this->assertEquals('test', $this->item->get());
    }

    public function testExpiresAt()
    {
        $time = time();

        $this->item->expiresAt((new \DateTime())->setTimestamp($time));

        $this->assertEquals($time, $this->item->getExpirationDate()->getTimestamp());
    }

    public function testExpiresAfter()
    {
        $time = time() + 1500;

        $this->item->expiresAfter(1500);

        $this->assertEquals($time, $this->item->getExpirationDate()->getTimestamp());
    }
}
