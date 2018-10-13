<?php

namespace PhpAbac\Test\Manager;

use PhpAbac\Configuration\Configuration;
use PhpAbac\Manager\AttributeManager;

use PhpAbac\Model\{
    Attribute,
    EnvironmentAttribute
};
use PhpAbac\Example\User;

class AttributeManagerTest extends \PHPUnit\Framework\TestCase
{
    /** @var AttributeManager **/
    private $manager;

    public function setUp()
    {
        $this->manager = new AttributeManager($this->getConfigurationMock());
    }
    
    public function testGetClassicAttribute()
    {
        $attribute = $this->manager->getAttribute('main_user.age');
        
        $this->assertInstanceOf(Attribute::class, $attribute);
        $this->assertEquals('user', $attribute->getType());
        $this->assertEquals('Age', $attribute->getName());
        $this->assertEquals('age', $attribute->getSlug());
        $this->assertEquals('age', $attribute->getProperty());
        $this->assertNull($attribute->getValue());
    }
    
    public function testGetEnvironmentAttribute()
    {
        $attribute = $this->manager->getAttribute('environment.service_state');
        
        $this->assertInstanceOf(EnvironmentAttribute::class, $attribute);
        $this->assertEquals('environment', $attribute->getType());
        $this->assertEquals('SERVICE_STATE', $attribute->getVariableName());
        $this->assertEquals('Statut du service', $attribute->getName());
        $this->assertEquals('statut-du-service', $attribute->getSlug());
        $this->assertNull($attribute->getValue());
    }
    
    public function testRetrieveClassicAttribute()
    {
        $this->assertEquals(18, $this->manager->retrieveAttribute(
            $this->manager->getAttribute('main_user.age'),
            (new User())->setAge(18)
        ));
    }
    
    public function testRetrieveEnvironmentAttribute()
    {
        putenv('SERVICE_STATE=OPEN');
        $this->assertEquals('OPEN', $this->manager->retrieveAttribute(
            $this->manager->getAttribute('environment.service_state'),
            (new User())->setAge(18)
        ));
    }
    
    public function getConfigurationMock()
    {
        $configurationMock = $this
            ->getMockBuilder(Configuration::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $configurationMock
            ->expects($this->any())
            ->method('getAttributes')
            ->willReturnCallback([$this, 'getAttributesMock'])
        ;
        return $configurationMock;
    }
    
    public function getAttributesMock()
    {
        return [
            'main_user' => [
                'class' => 'PhpAbac\Example\User',
                'type' => 'user',
                'fields' => [
                    'id' => [
                        'name' => 'ID'
                    ],
                    'age' => [
                        'name' => 'Age'
                    ],
                    'parentNationality' => [
                        'name' => 'Nationalité des parents'
                    ],
                    'hasDoneJapd' => [
                        'name' => 'JAPD'
                    ],
                    'hasDrivingLicense' => [
                        'name' => 'Permis de conduire'
                    ],
                    'visas' => [
                        'name' => 'Visas'
                    ]
                ]
            ],

            'vehicle' => [
                'class' => 'PhpAbac\Example\Vehicle',
                'type' => 'resource',
                'fields' => [
                    'origin' => [
                        'name' => 'Origine'
                    ],
                    'owner.id' => [
                        'name' => 'Propriétaire'
                    ],
                    'manufactureDate' => [
                        'name' => "Date de sortie d'usine"
                    ],
                    'lastTechnicalReviewDate' => [
                        'name' => 'Dernière révision technique'
                    ]
                ],
            ],
            'country' => [
                'class' => 'PhpAbac\Example\Country',
                'type' => 'resource',
                'fields' => [
                    'name' => [
                        'name' => 'Nom du pays'
                    ],
                    'code' => [
                        'name' => 'Code international'
                    ]
                ]
            ],
            'visa' => [
                'class' => 'PhpAbac\Example\Visa',
                'type' => 'resource',
                'fields' => [
                    'country.code' => [
                        'name' => 'Code Pays'
                    ],
                    'lastRenewal' => [
                        'name' => 'Dernier renouvellement'
                    ]
                ]
            ],
            'environment' => [
                'service_state' => [
                    'name' => 'Statut du service',
                    'variable_name' => 'SERVICE_STATE'
                ]
            ]
        ];
    }
}
