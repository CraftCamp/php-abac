<?php

namespace PhpAbac\Test;

use PhpAbac\Abac;

class AbacTest extends AbacTestCase {
    /** @var Abac **/
    protected $abac;
    
    public function setUp() {
        $this->abac = new Abac(new \PDO(
            'mysql:host=' . $GLOBALS['MYSQL_DB_HOST'] . ';' .
            'dbname=' . $GLOBALS['MYSQL_DB_DBNAME'],
            $GLOBALS['MYSQL_DB_USER'],
            $GLOBALS['MYSQL_DB_PASSWD'],
            [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            ]
        ));
        $this->loadFixture('policy_rules');
    }
    
    public function tearDown() {
        Abac::clearContainer();
    }
    
    public function testEnforce() {
        $this->assertTrue($this->abac->enforce('nationality-access', 1));
        $this->assertEquals([
            'japd'
        ], $this->abac->enforce('nationality-access', 2));
        
        // getenv() don't work in CLI scripts without putenv()
        putenv('SERVICE_STATE=OPEN');
        
        $this->assertTrue($this->abac->enforce('vehicle-homologation', 1, 1, ['proprietaire' => 1]));
        $this->assertEquals([
            'dernier-controle-technique'
        ],$this->abac->enforce('vehicle-homologation', 3, 2, ['proprietaire' => 3]));
        $this->assertEquals([
            'permis-de-conduire'
        ], $this->abac->enforce('vehicle-homologation', 4, 4, ['proprietaire' => 4]));
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