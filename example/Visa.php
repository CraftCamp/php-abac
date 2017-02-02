<?php

namespace PhpAbac\Example;

class Visa
{
    /** @var int **/
    protected $id;
    /** @var Country **/
    protected $country;
    /** @var \DateTime **/
    protected $createdAt;
    /** @var \DateTime **/
    protected $lastRenewal;
    
    /**
     * @param int $id
     * @return \PhpAbac\Example\Visa
     */
    public function setId($id)
    {
        $this->id = $id;
        
        return $this;
    }
    
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @param Country $country
     * @return \PhpAbac\Example\Visa
     */
    public function setCountry(Country $country)
    {
        $this->country = $country;
        
        return $this;
    }
    
    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }
    
    /**
     * @param \DateTime $createdAt
     * @return \PhpAbac\Example\Visa
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        
        return $this;
    }
    
    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    /**
     * @param \DateTime $lastRenewal
     * @return \PhpAbac\Example\Visa
     */
    public function setLastRenewal(\DateTime $lastRenewal)
    {
        $this->lastRenewal = $lastRenewal;
        
        return $this;
    }
    
    /**
     * @return \DateTime
     */
    public function getLastRenewal()
    {
        return $this->lastRenewal;
    }
}
