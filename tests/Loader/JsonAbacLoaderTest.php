<?php

namespace PhpAbac\Test\Manager;

use PhpAbac\Loader\JsonAbacLoader;
use Symfony\Component\Config\FileLocator;

class JsonAbacLoaderTest extends \PHPUnit\Framework\TestCase
{
    public function setUp()
    {
    }
    /**
     * @expectedException \Exception
     */
    public function testLoaderJsonInvalidJsonFile()
    {
        $JSON_loader = new JsonAbacLoader(new FileLocator(__DIR__.'/../fixtures/bad'));
        $JSON_loader->setCurrentDir(__DIR__.'/../fixtures/bad');
        $JSON_loader->import('policy_rules.json');
    }
    
    public function testLoaderJsonValidJsonFile()
    {
        $JSON_loader = new JsonAbacLoader(new FileLocator(__DIR__.'/../fixtures'));
        $JSON_loader->setCurrentDir(__DIR__.'/../fixtures');
        $this->assertThat($JSON_loader->import('policy_rules.json'), $this->isType('array'));
    }
    
    public function testSupports()
    {
        $JSON_loader = new JsonAbacLoader(new FileLocator(__DIR__.'/../fixtures'));
        $JSON_loader->setCurrentDir(__DIR__.'/../fixtures');
        $this->assertTrue($JSON_loader->supports('json.json'));
        $this->assertFalse($JSON_loader->supports('json.yaml'));
        $this->assertFalse($JSON_loader->supports('json.xml'));
    }
}
