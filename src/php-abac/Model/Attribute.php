<?php

namespace PhpAbac\Model;

class Attribute extends AbstractAttribute {
    /** @var string **/
    protected $table;
    /** @var string **/
    protected $column;
    /** @var string **/
    protected $criteriaColumn;
    
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
}