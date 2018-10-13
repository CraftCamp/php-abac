<?php

namespace PhpAbac\Configuration;

interface ConfigurationInterface
{
    public function getAttributes(): array;
    
    public function getRules(): array;
}