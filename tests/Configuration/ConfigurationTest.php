<?php

namespace PhpAbac\Test\Manager;

use Symfony\Component\Config\FileLocator;

use PhpAbac\Configuration\Configuration;

class ConfigurationTest extends \PHPUnit\Framework\TestCase
{
    public function setUp()
    {
        $this->manager = new Configuration(new FileLocator());
    }
    
    public function testParseConfigurationFile()
    {
        $this->manager->parseConfigurationFile([__DIR__.'/../fixtures/policy_rules.yml']);
        
        $this->assertCount(5, $this->manager->getAttributes());
        $this->assertCount(4, $this->manager->getRules());
    }
}
