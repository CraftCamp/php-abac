<?php

namespace PhpAbac\Test;

use PhpAbac\Abac;

class AbacTest extends \PHPUnit_Framework_TestCase {
    public function setUp() {
        new Abac(new \PDO(
            'mysql:host=' . $GLOBALS['MYSQL_DB_HOST'] . ';' .
            'dbname=' . $GLOBALS['MYSQL_DB_DBNAME'],
            $GLOBALS['MYSQL_DB_USER'],
            $GLOBALS['MYSQL_DB_PASSWD'],
            [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            ]
        ));
    }
    
    public function tearDown() {
        Abac::clearContainer();
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