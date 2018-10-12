<?php

namespace PhpAbac\Test\Manager;

use PhpAbac\Loader\YamlLoader;
use Symfony\Component\Config\FileLocator;

class YamlAbacLoaderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @expectedException \Exception
     */
    public function testLoaderYamlInvalidYamlFile()
    {
        $loader = new YamlLoader(new FileLocator(__DIR__.'/../fixtures/bad'));
        $loader->setCurrentDir(__DIR__.'/../fixtures/bad');
        $loader->import('policy_rules.yml');
    }
    
    public function testLoaderYamlValidYamlFile()
    {
        $loader = new YamlLoader(new FileLocator(__DIR__.'/../fixtures'));
        $loader->setCurrentDir(__DIR__.'/../fixtures');
        $this->assertThat($loader->import('policy_rules.yml'), $this->isType('array'));
    }
     
    public function testSupports()
    {
        $loader = new YamlLoader(new FileLocator(__DIR__.'/../fixtures'));
        $loader->setCurrentDir(__DIR__.'/../fixtures');
        $this->assertTrue($loader->supports('yaml.yaml'));
        $this->assertFalse($loader->supports('yaml.json'));
        $this->assertFalse($loader->supports('yaml.xml'));
    }
}
