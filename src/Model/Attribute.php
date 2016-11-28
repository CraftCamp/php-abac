<?php

namespace PhpAbac\Model;

class Attribute extends AbstractAttribute
{
    /** @var string **/
    protected $property;
    
    /**
     * @param string $property
     * @return static
     */
    public function setProperty($property) {
        $this->property = $property;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getProperty() {
        return $this->property;
    }
}
