<?php

namespace PhpAbac\Test\Manager;

use PhpAbac\Loader\JsonLoader;
use Symfony\Component\Config\FileLocator;

class JsonLoaderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @expectedException \Exception
     */
    public function testLoaderJsonInvalidJsonFile()
    {
        $loader = new JsonLoader(new FileLocator(__DIR__.'/../fixtures/bad'));
        $loader->setCurrentDir(__DIR__.'/../fixtures/bad');
        $loader->import('unexisting_policy_rules.json');
    }
    
    public function testLoaderJsonValidJsonFile()
    {
        $loader = new JsonLoader(new FileLocator(__DIR__.'/../fixtures'));
        $loader->setCurrentDir(__DIR__.'/../fixtures');
        $this->assertThat($loader->import('policy_rules.json'), $this->isType('array'));
    }
    
    public function testSupports()
    {
        $loader = new JsonLoader(new FileLocator(__DIR__.'/../fixtures'));
        $loader->setCurrentDir(__DIR__.'/../fixtures');
        $this->assertTrue($loader->supports('json.json'));
        $this->assertFalse($loader->supports('json.yaml'));
        $this->assertFalse($loader->supports('json.xml'));
    }
}
