<?php

namespace PhpAbac\Manager;

use PhpAbac\Loader\YamlAbacLoader;

use Symfony\Component\Config\FileLocatorInterface;

class ConfigurationManager {
    /** @var FileLocatorInterface **/
    protected $locator;
    /** @var string **/
    protected $format;
    /** @var array **/
    protected $loaders;
    /** @var array **/
    protected $rules;
    /** @var array **/
    protected $attributes;
    
    /**
     * @param FileLocatorInterface $locator
     * @param string $format
     */
    public function __construct(FileLocatorInterface $locator, $format = 'yaml') {
        $this->locator = $locator;
        $this->format = $format;
        $this->loaders['yaml'] = new YamlAbacLoader($locator);
    }
    
    public function parseConfigurationFile() {
        $config = $this->loaders[$this->format]->load('policy_rules.yml');
        $this->attributes = $config['attributes'];
        $this->rules = $config['rules'];
    }
    
    /**
     * @return array
     */
    public function getAttributes() {
        return $this->attributes;
    }
    
    /**
     * @return array
     */
    public function getRules() {
        return $this->rules;
    }
}