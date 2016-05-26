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
        $this->attributes = [];
        $this->rules = [];
        $this->loaders['yaml'] = new YamlAbacLoader($locator);
    }
    
    /**
     * @param array $configurationFiles
     */
    public function parseConfigurationFile($configurationFiles) {
        foreach($configurationFiles as $configurationFile) {
            $config = $this->loaders[$this->format]->load($configurationFile);
            if(isset($config['attributes'])) {
                $this->attributes = array_merge($this->attributes, $config['attributes']);
            }
            if(isset($config['rules'])) {
                $this->rules = array_merge($this->rules, $config['rules']);
            }
        }
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