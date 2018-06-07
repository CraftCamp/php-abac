<?php

namespace PhpAbac\Model;

class Attribute extends AbstractAttribute
{
    /** @var string **/
    protected $property;
    
    public function setProperty(string $property): Attribute
    {
        $this->property = $property;
        
        return $this;
    }
    
    public function getProperty(): string
    {
        return $this->property;
    }
}
