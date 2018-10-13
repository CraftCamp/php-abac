<?php

namespace PhpAbac\Test\Manager;

use PhpAbac\Configuration\Configuration;
use PhpAbac\Manager\{
    PolicyRuleManager,
    AttributeManager
};
use PhpAbac\Model\{
    Attribute,
    PolicyRule,
    PolicyRuleAttribute
};
use Symfony\Component\Config\FileLocator;

class PolicyRuleManagerTest extends \PHPUnit\Framework\TestCase
{
    /** @var PolicyRuleManager **/
    private $manager;

    public function setUp()
    {
        $this->manager = new PolicyRuleManager(
            $this->getConfigurationMock(),
            $this->getAttributeManagerMock()
        );
    }

    public function testGetRule()
    {
        $countries = include('tests/fixtures/countries.php');
        $visas = include('tests/fixtures/visas.php');
        $users = include('tests/fixtures/users.php');
        $vehicles = include('tests/fixtures/vehicles.php');

        $policyRule_a = $this->manager->getRule('vehicle-homologation', $users[0], $vehicles[0]);

        $policyRule = $policyRule_a[0];

        $this->assertInstanceof(PolicyRule::class, $policyRule);
        $this->assertEquals('vehicle-homologation', $policyRule->getName());
        $this->assertCount(6, $policyRule->getPolicyRuleAttributes());

        $policyRuleAttribute = $policyRule->getPolicyRuleAttributes()[0];

        $this->assertInstanceOf(PolicyRuleAttribute::class, $policyRuleAttribute);
        $this->assertInstanceOf(Attribute::class, $policyRuleAttribute->getAttribute());
        $this->assertEquals('boolean', $policyRuleAttribute->getComparisonType());
        $this->assertEquals('boolAnd', $policyRuleAttribute->getComparison());
        $this->assertTrue($policyRuleAttribute->getValue());
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
            ->method('getRules')
            ->willReturnCallback([$this, 'getRulesMock'])
        ;
        return $configurationMock;
    }
    
    public function getRulesMock()
    {
        return [
            'nationality-access' => [
                'attributes' => [
                    'main_user.age' => [
                        'comparison_type' => 'numeric',
                        'comparison' => 'isGreaterThan',
                        'value' => 18
                    ],
                    'main_user.parentNationality' => [
                        'comparison_type' => 'string',
                        'comparison' => 'isEqual',
                        'value' => 'FR'
                    ],
                    'main_user.hasDoneJapd' => [
                        'comparison_type' => 'boolean',
                        'comparison' => 'boolAnd',
                        'value' => true
                    ]
                ]
            ],
            'vehicle-homologation' => [
                'attributes' => [
                    'main_user.hasDrivingLicense' => [
                        'comparison_type' => 'boolean',
                        'comparison' => 'boolAnd',
                        'value' => true
                    ],
                    'vehicle.lastTechnicalReviewDate' => [
                        'comparison_type' => 'datetime',
                        'comparison' => 'isMoreRecentThan',
                        'value' => '-2Y'
                    ],
                    'vehicle.manufactureDate' => [
                        'comparison_type' => 'datetime',
                        'comparison' => 'isMoreRecentThan',
                        'value' => '-25Y'
                    ],
                    'vehicle.owner.id' => [
                        'comparison_type' => 'user',
                        'comparison' => 'isFieldEqual',
                        'value' => 'main_user.id'
                    ],
                    'vehicle.origin' => [
                        'comparison_type' => 'array',
                        'comparison' => 'isIn',
                        'value' => ["FR", "DE", "IT", "L", "GB", "P", "ES", "NL", "B"]
                    ],
                    'environment.service_state' => [
                        'comparison_type' => 'string',
                        'comparison' => 'isEqual',
                        'value' => 'OPEN'
                    ]
                ]
            ],
            'gunlaw' => [
                'attributes' => [
                    'main_user.age' => [
                        'comparison_type' => 'numeric',
                        'comparison' => 'isGreaterThan',
                        'value' => 21
                    ]
                ]
            ],
            'travel-to-foreign-country' => [
                'attributes' => [
                    'main_user.age' => [
                        'comparison_type' => 'numeric',
                        'comparison' => 'isGreaterThan',
                        'value' => 18
                    ],
                    'main_user.visas' => [
                        'comparison_type' => 'array',
                        'comparison' => 'contains',
                        'with' => [
                            'visa.country.code' => [
                                'comparison_type' => 'string',
                                'comparison' => 'isEqual',
                                'value' => 'dynamic'
                            ],
                            'visa.lastRenewal' => [
                                'comparison_type' => 'datetime',
                                'comparison' => 'isMoreRecentThan',
                                'value' => '-1Y'
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
    
    public function getAttributeManagerMock()
    {
        $attributeManagerMock = $this
            ->getMockBuilder(AttributeManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $attributeManagerMock
            ->expects($this->any())
            ->method('getAttribute')
            ->willReturnCallback([$this, 'getAttributeMock'])
        ;
        return $attributeManagerMock;
    }
    
    public function getAttributeMock($name)
    {
        return
            (new Attribute())
            ->setName($name)
            ->setSlug($name)
            ->setType((strpos('main_user', $name)) ? 'user' : 'resource')
            ->setValue($this->getAttributeValueMock($name))
        ;
    }
    
    public function getAttributeValueMock($name)
    {
        switch ($name) {
            case 'main_user.hasDrivingLicense':
                return true;
            case 'vehicle.lastTechnicalReviewDate':
                return new \DateTime('-6 months');
            case 'vehicle.manufactureDate':
                return new \DateTime('-2 years');
            case 'vehicle.owner.id':
                return 1;
            case 'vehicle.origin':
                return 'FR';
            case 'environment.service_state':
                return 'OPEN';
            default:
                var_dump($name);
                break;
        }
    }
}
