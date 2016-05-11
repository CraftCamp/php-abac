<?php

namespace PhpAbac\Example;

class Vehicle {
    /** @var int **/
    private $id;
    /** @var \PhpAbac\Example\User **/
    private $owner;
    /** @var string **/
    private $brand;
    /** @var string **/
    private $model;
    /** @var \DateTime **/
    private $lastTechnicalReviewDate;
    /** @var \DateTime **/
    private $manufactureDate;
    /** @var string **/
    private $origin;
    /** @var string **/
    private $engineType;
    /** @var string **/
    private $ecoClass;

    /**
     * @param int $id
     * @return \PhpAbac\Example\Vehicle
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
     * @param \PhpAbac\Example\User $owner
     * @return \PhpAbac\Example\Vehicle
     */
    public function setOwner(User $owner) {
        $this->owner = $owner;
        
        return $this;
    }
    
    /**
     * @return \PhpAbac\Example\User
     */
    public function getOwner() {
        return $this->owner;
    }
    
    /**
     * @param string $brand
     * @return \PhpAbac\Example\Vehicle
     */
    public function setBrand($brand) {
        $this->brand = $brand;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getBrand() {
        return $this->brand;
    }
    
    /**
     * 
     * @param string $model
     * @return \PhpAbac\Example\Vehicle
     */
    public function setModel($model) {
        $this->model = $model;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getModel() {
        return $this->model;
    }
    
    /**
     * @param \DateTime $lastTechnicalReviewDate
     * @return \PhpAbac\Example\Vehicle
     */
    public function setLastTechnicalReviewDate(\DateTime $lastTechnicalReviewDate) {
        $this->lastTechnicalReviewDate = $lastTechnicalReviewDate;
        
        return $this;
    }
    
    /**
     * @return \DateTime
     */
    public function getLastTechnicalReviewDate() {
        return $this->lastTechnicalReviewDate;
    }
    
    /**
     * @param \DateTime $manufactureDate
     * @return \PhpAbac\Example\Vehicle
     */
    public function setManufactureDate(\DateTime $manufactureDate) {
        $this->manufactureDate = $manufactureDate;
        
        return $this;
    }
    
    public function getManufactureDate() {
        return $this->manufactureDate;
    }
    
    public function setOrigin($origin) {
        $this->origin = $origin;
        
        return $this;
    }
    
    public function getOrigin() {
        return $this->origin;
    }
    
    public function setEngineType($engineType) {
        $this->engineType = $engineType;
        
        return $this;
    }
    
    public function getEngineType() {
        return $this->engineType;
    }
    
    public function setEcoClass($ecoClass) {
        $this->ecoClass = $ecoClass;
        
        return $this;
    }
    
    public function getEcoClass() {
        return $this->ecoClass;
    }
}