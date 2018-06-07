<?php

namespace PhpAbac\Manager;

use PhpAbac\Loader\{
    AbacLoader,
    JsonAbacLoader,
    YamlAbacLoader
};

use Symfony\Component\Config\FileLocatorInterface;

class ConfigurationManager
{
    /** @var FileLocatorInterface * */
    protected $locator;
    /** @var AbacLoader[] * */
    protected $loaders;
    /** @var array * */
    protected $rules = [];
    /** @var array * */
    protected $attributes = [];
    /** @var array List of File Already Loaded */
    protected $loadedFiles = [];
    
    public function __construct(FileLocatorInterface $locator, array $format = ['yaml', 'json'])
    {
        $this->locator = $locator;
        if (in_array('yaml', $format)) {
            $this->loaders[ 'yaml' ] = new YamlAbacLoader($locator, $this);
        }
        if (in_array('json', $format)) {
            $this->loaders[ 'json' ] = new JsonAbacLoader($locator, $this);
        }
    }
    
    public function setConfigPathRoot(string $configDir = null)
    {
        foreach ($this->loaders as $loader) {
            $loader->setCurrentDir($configDir);
        }
    }
    
    public function parseConfigurationFile(array $configurationFiles)
    {
        foreach ($configurationFiles as $configurationFile) {
            $config = $this->getLoader($configurationFile)->import($configurationFile, pathinfo($configurationFile, PATHINFO_EXTENSION));
            
            if (in_array($config['path'], $this->loadedFiles)) {
                continue;
            }
            
            $this->loadedFiles[] = $config['path'];
            
            if (isset($config['@import'])) {
                $this->parseConfigurationFile($config['@import']);
                unset($config['@import']);
            }
            
            if (isset($config[ 'attributes' ])) {
                $this->attributes = array_merge($this->attributes, $config[ 'attributes' ]);
            }
            if (isset($config[ 'rules' ])) {
                $this->rules = array_merge($this->rules, $config[ 'rules' ]);
            }
        }
    }
    
    /**
     * Function to retrieve the good loader for the configuration file
     */
    private function getLoader(string $configurationFile): AbacLoader
    {
        foreach ($this->loaders as $AbacLoader) {
            if ($AbacLoader::supportsExtension($configurationFile)) {
                return $AbacLoader;
            }
        }
        throw new \Exception('Loader not found for the file ' . $configurationFile);
    }
    
    public function getAttributes(): array
    {
        return $this->attributes;
    }
    
    public function getRules(): array
    {
        return $this->rules;
    }
}
