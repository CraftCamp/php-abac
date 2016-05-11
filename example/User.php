<?php

namespace PhpAbac\Example;

class User {
    /** @var int **/
    private $id;
    /** @var string **/
    private $name;
    /** @var int **/
    private $age;
    /** @var string **/
    private $parentNationality;
    /** @var bool **/
    private $hasDoneJapd;
    /** @var bool **/
    private $hasDrivingLicense;

    /**
     * @param int $id
     * @return \PhpAbac\Example\User
     */
    public function setId($id) {
        $this->id = $id;
        
        return $this;
    }
    
    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * @param string $name
     * @return \PhpAbac\Example\User
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
     * @param int $age
     * @return \PhpAbac\Example\User
     */
    public function setAge($age) {
        $this->age = $age;
        
        return $this;
    }
    
    /**
     * @return int
     */
    public function getAge() {
        return $this->age;
    }
    
    /**
     * @param string $parentNationality
     * @return \PhpAbac\Example\User
     */
    public function setParentNationality($parentNationality) {
        $this->parentNationality = $parentNationality;
        
        return $this;
    }
    
    /**
     * @return bool
     */
    public function getParentNationality() {
        return $this->parentNationality;
    }
    
    /**
     * @param bool $hasDoneJapd
     * @return \PhpAbac\Example\User
     */
    public function setHasDoneJapd($hasDoneJapd) {
        $this->hasDoneJapd = $hasDoneJapd;
        
        return $this;
    }
    
    /**
     * @return bool
     */
    public function getHasDoneJapd() {
        return $this->hasDoneJapd;
    }
    
    /**
     * @param bool $hasDrivingLicense
     * @return \PhpAbac\Example\User
     */
    public function setHasDrivingLicense($hasDrivingLicense) {
        $this->hasDrivingLicense = $hasDrivingLicense;
        
        return $this;
    }
    
    /**
     * @return bool
     */
    public function getHasDrivingLicense() {
        return $this->hasDrivingLicense;
    }
}