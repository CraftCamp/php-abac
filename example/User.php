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
    /** @var array **/
    private $visas;
    /** @var bool **/
    private $hasDoneJapd;
    /** @var bool **/
    private $hasDrivingLicense;
    /** @var string Iso code of user country */
    private $country;

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
     * @param \PhpAbac\Example\Visa $visa
     * @return \PhpAbac\Example\User
     */
    public function addVisa(Visa $visa) {
        $this->visas[$visa->getId()] = $visa;
        
        return $this;
    }
    
    /**
     * @param \PhpAbac\Example\Visa $visa
     * @return \PhpAbac\Example\User
     */
    public function removeVisa(Visa $visa) {
        if(isset($this->visas[$visa->getId()])) {
            unset($this->visas[$visa->getId()]);
        }
        return $this;
    }
    
    /**
     * @return array
     */
    public function getVisas() {
        return $this->visas;
    }
	
	/**
	 * Return a specific visa
	 *
	 * @param Visa $visa
	 *
	 * @return mixed|null
	 */
    public function getVisa($country_code) {
    	/** @var Visa $visa */
    	$visas = [];
		foreach($this->visas as $visa) {
    		if ($visa->getCountry()->getCode() == $country_code)
				$visas[] = $visa;
		}
		return $visas;
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


    /**
     * Function to set the iso code of the user country
     *
     * @param $country
     */
    public function setCountry($country) {
        $this->country = $country;

        return $this;
    }

    /**
     * @return string Iso code of the user country
     */
    public function getCountry() {
        return $this->country;
    }
}