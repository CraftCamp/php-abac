<?php

namespace PhpAbac\Example;

class Country {
    /** @var string **/
    protected $name;
    /** @var string **/
    protected $code;
    
    /**
     * @param string $name
     * @return \PhpAbac\Example\Country
     */
    public function setName($name) {
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * @param string $code
     * @return \PhpAbac\Example\Country
     */
    public function setCode($code) {
        $this->code = $code;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getCode() {
        return $this->code;
    }
}