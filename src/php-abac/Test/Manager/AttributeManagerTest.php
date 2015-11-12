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
        $this->manager->create('Licence d\'équitation', 'users', 'has_horse_license', 'id');
        
        $data =
            Abac::get('pdo-connection')
            ->query(
                'SELECT * FROM abac_attributes_data ad ' .
                'INNER JOIN abac_attributes a ON a.id = ad.id '.
                'WHERE a.id = LAST_INSERT_ID()'
            )
            ->fetch(\PDO::FETCH_ASSOC)
        ;
        $this->assertEquals('Licence d\'équitation', $data['name']);
        $this->assertEquals('licence-d-equitation', $data['slug']);
        $this->assertEquals('users', $data['table_name']);
        $this->assertEquals('has_horse_license', $data['column_name']);
        $this->assertEquals('id', $data['criteria_column']);
    }
}