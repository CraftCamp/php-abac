<?php

namespace PhpAbac\Test\Manager;

use PhpAbac\Configuration\Configuration;

class ConfigurationTest extends \PHPUnit\Framework\TestCase
{
    public function testParseFiles()
    {
        $configuration = new Configuration([__DIR__.'/../fixtures/policy_rules.yml']);
        
        $this->assertCount(5, $configuration->getAttributes());
        $this->assertCount(4, $configuration->getRules());
    }
}
