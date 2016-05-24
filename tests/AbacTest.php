<?php

namespace PhpAbac\Test;

use PhpAbac\Abac;

class AbacTest extends AbacTestCase
{
    /** @var Abac **/
    protected $abac;

    public function setUp()
    {
        $this->abac = new Abac($this->getConnection());
        $this->loadFixture('policy_rules');
    }

    public function tearDown()
    {
        Abac::clearContainer();
    }

    public function testEnforce()
    {
        $users = include('tests/fixtures/users.php');
        $vehicles = include('tests/fixtures/vehicles.php');
        $this->assertTrue($this->abac->enforce('nationality-access', $users[3]));
        $this->assertEquals([
            'japd',
        ], $this->abac->enforce('nationality-access', $users[1]));

        // getenv() don't work in CLI scripts without putenv()
        putenv('SERVICE_STATE=OPEN');
        
        $this->assertTrue($this->abac->enforce('vehicle-homologation', $users[0], $vehicles[0], [
            'dynamic_attributes' => ['proprietaire' => 1]
        ]));
        $this->assertEquals([
            'dernier-controle-technique'
        ],$this->abac->enforce('vehicle-homologation', $users[2], $vehicles[1], [
            'dynamic_attributes' => ['proprietaire' => 3]
        ]));
        $this->assertEquals([
            'permis-de-conduire'
        ], $this->abac->enforce('vehicle-homologation', $users[3], $vehicles[3], [
            'dynamic_attributes' => ['proprietaire' => 4]
        ]));
    }
    
    public function testEnforceWithCache() {
        $users = include('tests/fixtures/users.php');
        $vehicles = include('tests/fixtures/vehicles.php');
        
        $this->assertTrue($this->abac->enforce('nationality-access', $users[3], null, [
            'cache_result' => true,
            'cache_ttl' => 100,
            'cache_driver' => 'memory'
        ]));
        $this->assertEquals([
            'japd'
        ], $this->abac->enforce('nationality-access', $users[1], null, [
            'cache_result' => true,
            'cache_ttl' => 100,
            'cache_driver' => 'memory'
        ]));
        $cacheItems = Abac::get('cache-manager')->getItemPool('memory')->getItems([
            'nationality-access-4-',
            'nationality-access-2-'
        ]);
        $this->assertCount(2, $cacheItems);
        $this->assertArrayHasKey('nationality-access-4-', $cacheItems);
        $this->assertInstanceOf('PhpAbac\\Cache\\Item\\MemoryCacheItem', $cacheItems['nationality-access-4-']);
        $this->assertEquals('nationality-access-4-', $cacheItems['nationality-access-4-']->getKey());
        $this->assertEquals(true, $cacheItems['nationality-access-4-']->get());
        $this->assertEquals(['japd'], $cacheItems['nationality-access-2-']->get());
    }
    
    public function testContainer() {
        $item = new \stdClass();
        $item->property = 'test';
        // Test Set method
        Abac::set('test-item', $item);
        // Test Has method
        $this->assertTrue(Abac::has('test-item'));
        // Test Get method
        $containedItem = Abac::get('test-item');

        $this->assertInstanceof('StdClass', $containedItem);
        $this->assertEquals('test', $containedItem->property);
        // Test clearContainer
        Abac::clearContainer();
        $this->assertFalse(Abac::has('test-item'));
    }
}
