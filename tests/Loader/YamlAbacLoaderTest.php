<?php

namespace PhpAbac\Test\Manager;

use PhpAbac\Loader\YamlAbacLoader;
use Symfony\Component\Config\FileLocator;

class YamlAbacLoaderTest extends \PHPUnit\Framework\TestCase
{
    public function setUp()
    {
    }
    /**
     * @expectedException \Exception
     */
    public function testLoaderYamlInvalidYamlFile()
    {
        $YAML_loader = new YamlAbacLoader(new FileLocator(__DIR__.'/../fixtures/bad'));
        $YAML_loader->setCurrentDir(__DIR__.'/../fixtures/bad');
        $YAML_loader->import('policy_rules.yml');
    }
    
    public function testLoaderYamlValidYamlFile()
    {
        $YAML_loader = new YamlAbacLoader(new FileLocator(__DIR__.'/../fixtures'));
        $YAML_loader->setCurrentDir(__DIR__.'/../fixtures');
        $this->assertThat($YAML_loader->import('policy_rules.yml'), $this->isType('array'));
    }
     
    public function testSupports()
    {
        $YAML_loader = new YamlAbacLoader(new FileLocator(__DIR__.'/../fixtures'));
        $YAML_loader->setCurrentDir(__DIR__.'/../fixtures');
        $this->assertTrue($YAML_loader->supports('yaml.yaml'));
        $this->assertFalse($YAML_loader->supports('yaml.json'));
        $this->assertFalse($YAML_loader->supports('yaml.xml'));
    }
}
