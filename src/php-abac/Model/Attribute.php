<?php

namespace PhpAbac\Model;

class Attribute implements AttributeInterface {
    /** @var string **/
    protected $table;
    /** @var string **/
    protected $column;
    /** @var mixed **/
    protected $value;
    
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