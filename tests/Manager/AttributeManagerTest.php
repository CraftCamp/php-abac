<?php

namespace PhpAbac\Test\Manager;

use PhpAbac\Abac;
use PhpAbac\Test\AbacTestCase;
use PhpAbac\Model\Attribute;
use PhpAbac\Model\EnvironmentAttribute;

class AttributeManagerTest extends AbacTestCase
{
    /** @var \PhpAbac\Manager\AttributeManager **/
    private $manager;

    /**
     * @var Abac
     */
    private $abac;

    public function setUp()
    {
        $this->abac = new Abac($this->getConnection());

        $this->loadFixture('policy_rules');

        $this->manager = Abac::get('attribute-manager');
    }

    public function tearDown()
    {
        Abac::clearContainer();
    }

    public function testCreate()
    {
        $attribute =
            (new Attribute())
            ->setName('Licence d\'equitation')
            ->setProperty('hasHorseLicense')
        ;
        $this->manager->create($attribute);

        $id = $this->getConnection()->lastInsertId('abac_attributes');

        $data =
            Abac::get('pdo-connection')
            ->query(
                'SELECT * FROM abac_attributes_data ad '.
                'INNER JOIN abac_attributes a ON a.id = ad.id '.
                'WHERE a.id = '.$id
            )
            ->fetch(\PDO::FETCH_ASSOC)
        ;
        $this->assertEquals('Licence d\'equitation', $data['name']);
        $this->assertEquals('licence-d-equitation', $data['slug']);
        $this->assertEquals('hasHorseLicense', $data['property']);
    }

    public function testCreateEnvironmentAttribute()
    {
        $attribute =
            (new EnvironmentAttribute())
            ->setName('Server Protocol')
            ->setVariableName('SERVER_PROTOCOL')
        ;
        $this->manager->create($attribute);

        $id = $this->getConnection()->lastInsertId('abac_environment_attributes');

        $data =
            Abac::get('pdo-connection')
            ->query(
                'SELECT * FROM abac_attributes_data ad '.
                'INNER JOIN abac_environment_attributes a ON a.id = ad.id '.
                'WHERE a.id = '.$id
            )
            ->fetch(\PDO::FETCH_ASSOC)
        ;
        $this->assertEquals('Server Protocol', $data['name']);
        $this->assertEquals('SERVER_PROTOCOL', $data['variable_name']);
    }
}
