<?php

namespace PhpAbac\Configuration;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderInterface;

use PhpAbac\Loader\JsonLoader;
use PhpAbac\Loader\YamlLoader;

class Configuration implements ConfigurationInterface
{
    /**
     * @var array
     */
    protected $loaders = [];
    /**
     * @var array
     */
    protected $rules = [];
    /**
     * @var array
     */
    protected $attributes = [];
    /**
     * @var array List of File Already Loaded
     */
    protected $loadedFiles = [];
    //TODO: need to make it more flexible
    const LOADERS = [
        JsonLoader::class,
        YamlLoader::class
    ];

    /**
     * Configuration constructor.
     *
     * @param array $configurationFiles
     * @param string|null $configDir
     *
     * @throws \Symfony\Component\Config\Exception\FileLoaderImportCircularReferenceException
     * @throws \Symfony\Component\Config\Exception\LoaderLoadException
     */
    public function __construct(array $configurationFiles, string $configDir = null)
    {
        $this->initLoaders($configDir);
        $this->parseFiles($configurationFiles);
    }
    
    protected function initLoaders(string $configDir = null)
    {
        $locator = new FileLocator($configDir);
        foreach (self::LOADERS as $loaderClass) {
            /**
             * @var YamlLoader|JsonLoader $loader
             */
            $loader = new $loaderClass($locator);
            $loader->setCurrentDir($configDir);
            $this->loaders[] = $loader;
        }
    }

    /**
     * @param array $configurationFiles
     *
     * @throws \Symfony\Component\Config\Exception\FileLoaderImportCircularReferenceException
     * @throws \Symfony\Component\Config\Exception\LoaderLoadException
     */
    protected function parseFiles(array $configurationFiles)
    {
        foreach ($configurationFiles as $configurationFile) {
            $config = $this->getLoader($configurationFile)
                ->import(
                    $configurationFile,
                    pathinfo($configurationFile, PATHINFO_EXTENSION)
                );
            
            if (in_array($configurationFile, $this->loadedFiles)) {
                continue;
            }
            
            $this->loadedFiles[] = $configurationFile;
            
            if (isset($config['@import'])) {
                $this->parseFiles($config['@import']);
                unset($config['@import']);
            }
            
            if (isset($config['attributes'])) {
                $this->attributes = array_merge($this->attributes, $config['attributes']);
            }
            if (isset($config['rules'])) {
                $this->rules = array_merge($this->rules, $config['rules']);
            }
        }
    }

    /**
     * @param string $configurationFile
     *
     * @return LoaderInterface|YamlLoader|JsonLoader
     * @throws \Exception
     */
    protected function getLoader(string $configurationFile): LoaderInterface
    {
        foreach ($this->loaders as $abacLoader) {
            if ($abacLoader->supports($configurationFile)) {
                return $abacLoader;
            }
        }
        throw new \Exception('Loader not found for the file ' . $configurationFile);
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return $this->rules;
    }
}
