<?php

namespace PhpAbac\Test\Manager;

use Symfony\Component\Config\FileLocator;

use PhpAbac\Manager\ConfigurationManager;

class ConfigurationManagerTest extends \PHPUnit_Framework_TestCase {
    public function setUp() {
        $this->manager = new ConfigurationManager(new FileLocator());
    }
    
    public function testParseConfigurationFile() {
        $this->manager->parseConfigurationFile([__DIR__.'/../fixtures/policy_rules.yml']);
        
        $this->assertCount(4, $this->manager->getAttributes());
        $this->assertCount(4, $this->manager->getRules());
    }
}