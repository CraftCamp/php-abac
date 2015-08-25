<?php

namespace PhpAbac\Test\Manager;

use PhpAbac\Abac;

class AttributeManagerTest extends \PHPUnit_Framework_TestCase {
    /** @var \PhpAbac\Manager\AttributeManager **/
    private $manager;
    
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
        
        $this->manager = Abac::get('attribute-manager');
    }
    
    public function tearDown() {
        Abac::clearContainer();
    }
    
    public function testCreate() {
        $this->manager->create('test-attribute', 'abac_policy_rules', 'name', 'id');
        
        $data = Abac::get('pdo-connection')->query('SELECT * FROM abac_attributes WHERE name = "test-attribute"')->fetchAll();
        
        $this->assertCount(1, $data);
        $this->assertEquals('test-attribute', $data[0]['name']);
        $this->assertEquals('abac_policy_rules', $data[0]['table_name']);
        $this->assertEquals('name', $data[0]['column_name']);
        $this->assertEquals('id', $data[0]['criteria_column']);
    }
}