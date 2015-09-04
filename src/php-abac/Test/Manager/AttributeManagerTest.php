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
        
        $data =
            Abac::get('pdo-connection')
            ->query(
                'SELECT * FROM abac_attributes_data ad ' .
                'INNER JOIN abac_attributes a ON a.id = ad.id '.
                'WHERE ad.name = "JAPD"'
            )
            ->fetchAll()
        ;
        
        $this->assertCount(1, $data);
        $this->assertEquals('JAPD', $data[0]['name']);
        $this->assertEquals('abac_test_user', $data[0]['table_name']);
        $this->assertEquals('has_done_japd', $data[0]['column_name']);
        $this->assertEquals('id', $data[0]['criteria_column']);
    }
}