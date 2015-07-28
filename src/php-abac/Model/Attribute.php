<?php

namespace PhpAbac\Model;

class Attribute {
    /** @var integer **/
    protected $id;
    /** @var string **/
    protected $table;
    /** @var string **/
    protected $column;
    /** @var string **/
    protected $idColumn;
    /** @var mixed **/
    protected $value;
    
    /**
     * @return integer
     */
    public function getId() {
        return $this->id;
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
     * @param string $idColumn
     * @return \PhpAbac\Model\Attribute
     */
    public function setIdColumn($idColumn) {
        $this->idColumn = $idColumn;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getIdColumn() {
        return $this->idColumn;
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