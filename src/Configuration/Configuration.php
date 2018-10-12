<?php

namespace PhpAbac\Configuration;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderInterface;

use PhpAbac\Loader\JsonAbacLoader;
use PhpAbac\Loader\YamlAbacLoader;

class Configuration
{
    /** @var AbacLoader[] * */
    protected $loaders = [];
    /** @var array * */
    protected $rules = [];
    /** @var array * */
    protected $attributes = [];
    /** @var array List of File Already Loaded */
    protected $loadedFiles = [];
    
    const LOADERS = [
        JsonAbacLoader::class,
        YamlAbacLoader::class
    ];
    
    public function __construct(string $configDir = null)
    {
        $this->initLoaders($configDir);
    }
    
    public function initLoaders(string $configDir = null)
    {
        $locator = new FileLocator($configDir);
        foreach (self::LOADERS as $loaderClass) {
            $loader = new $loaderClass($locator);
            $loader->setCurrentDir($configDir);
            $this->loaders[] = $loader;
        }
    }
    
    public function parseConfigurationFile(array $configurationFiles)
    {
        foreach ($configurationFiles as $configurationFile) {
            $config = $this->getLoader($configurationFile)->import($configurationFile, pathinfo($configurationFile, PATHINFO_EXTENSION));
            
            if (in_array($configurationFile, $this->loadedFiles)) {
                continue;
            }
            
            $this->loadedFiles[] = $configurationFile;
            
            if (isset($config['@import'])) {
                $this->parseConfigurationFile($config['@import']);
                unset($config['@import']);
            }
            
            if (isset($config['attributes'])) {
                $this->attributes = array_merge($this->attributes, $config[ 'attributes' ]);
            }
            if (isset($config['rules'])) {
                $this->rules = array_merge($this->rules, $config[ 'rules' ]);
            }
        }
    }
    
    private function getLoader(string $configurationFile): LoaderInterface
    {
        foreach ($this->loaders as $abacLoader) {
            if ($abacLoader->supports($configurationFile)) {
                return $abacLoader;
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
