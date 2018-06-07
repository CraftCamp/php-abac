<?php

namespace PhpAbac\Test\Manager;

use Symfony\Component\Config\FileLocator;

use PhpAbac\Manager\{
    AttributeManager,
    ConfigurationManager
};
use PhpAbac\Model\{
    Attribute,
    EnvironmentAttribute
};
use PhpAbac\Example\User;

class AttributeManagerTest extends \PHPUnit\Framework\TestCase
{
    /** @var \PhpAbac\Manager\AttributeManager **/
    private $manager;

    public function setUp()
    {
        $configuration = new ConfigurationManager(new FileLocator());
        $configuration->parseConfigurationFile([__DIR__.'/../fixtures/policy_rules.yml']);
        
        $this->manager = new AttributeManager($configuration->getAttributes());
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
        $this->assertEquals('OPEN', $this->manager->retrieveAttribute(
            $this->manager->getAttribute('environment.service_state'),
            (new User())->setAge(18)
        ));
    }
}
