<?php

namespace PhpAbac\Model;

class Attribute {
    /** @var integer **/
    protected $id;
    /** @var string **/
    protected $name;
    /** @var string **/
    protected $table;
    /** @var string **/
    protected $column;
    /** @var string **/
    protected $criteriaColumn;
    /** @var \DateTime **/
    protected $createdAt;
    /** @var \DateTime **/
    protected $updatedAt;
    /** @var mixed **/
    protected $value;
    
    /**
     * @param integer $id
     * @return \PhpAbac\Model\Attribute
     */
    public function setId($id) {
        $this->id = $id;
        
        return $this;
    }
    
    /**
     * @return integer
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * @param string $name
     * @return \PhpAbac\Model\Attribute
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
     * @param string $table
     * @return \PhpAbac\Model\Attribute
     */
    public function setTable($table) {
        $this->table = $table;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getTable() {
        return $this->table;
    }
    
    /**
     * @param string $column
     * @return \PhpAbac\Model\Attribute
     */
    public function setColumn($column) {
        $this->column = $column;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getColumn() {
        return $this->column;
    }
    
    
    /**
     * @param string $criteriaColumn
     * @return \PhpAbac\Model\Attribute
     */
    public function setCriteriaColumn($criteriaColumn) {
        $this->criteriaColumn = $criteriaColumn;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getCriteriaColumn() {
        return $this->criteriaColumn;
    }
    
    /**
     * @param \DateTime $datetime
     * @return \PhpAbac\Model\Attribute
     */
    public function setCreatedAt(\DateTime $datetime) {
        $this->createdAt = $datetime;
        
        return $this;
    }
    
    /**
     * @return \DateTime
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }
    
    /**
     * @param \DateTime $datetime
     * @return \PhpAbac\Model\Attribute
     */
    public function setUpdatedAt(\DateTime $datetime) {
        $this->updatedAt = $datetime;
        
        return $this;
    }
    
    /**
     * @return \DateTime
     */
    public function getUpdatedAt() {
        return $this->updatedAt;
    }
    
    /**
     * @param mixed $value
     * @return \PhpAbac\Model\Attribute
     */
    public function setValue($value) {
        $this->value = $value;
        
        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getValue() {
        return $this->value;
    }
}