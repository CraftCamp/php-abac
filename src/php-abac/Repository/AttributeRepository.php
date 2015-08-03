<?php

namespace PhpAbac\Repository;

use PhpAbac\Model\Attribute;

class AttributeRepository extends Repository {
    /**
     * @param integer $attributeId
     * @return Attribute
     */
    public function findAttribute($attributeId) {
        $statement = $this->query(
            'SELECT name, table, column, criteria_column, created_at, updated_at FROM abac_attributes WHERE id = :id'
        , ['id' => $attributeId]);
        $data = $statement->fetch();
        
        return
            (new Attribute())
            ->setName($data['name'])
            ->setTable($data['table'])
            ->setColumn($data['column'])
            ->setCriteriaColumn($data['criteria_column'])
            ->setCreatedAt($data['created_at'])
            ->setUpdatedAt($data['updated_at'])
        ;
    }
    
    /**
     * @param Attribute &$attribute
     * @param mixed $criteria
     */
    public function retrieveAttribute(Attribute &$attribute, $criteria) {
        $statement = $this->query(
            'SELECT :column FROM :table WHERE :criteria_column = :criteria'
        , [
            'column' => $attribute->getColumn(),
            'table' => $attribute->getTable(),
            'criteria' => $criteria,
            'criteria_column' => $attribute->getCriteriaColumn()
        ]);
        $data = $statement->fetch();
        $attribute->setValue($data[$attribute->getColumn()]);
    }
    
    /**
     * @param string $name
     * @param string $table
     * @param string $column
     * @param string $criteriaColumn
     */
    public function createAttribute($name, $table, $column, $criteriaColumn) {
        $datetime = new DateTime();
        
        $this->insert(
            'INSERT INTO abac_attributes(name, table, column, criteria_column, created_at, updated_at) ' .
            'VALUES(:name, :table, :column, :criteria_column, :created_at, :updated_at)'
        , [
            'name' => $name,
            'table' => $table,
            'column' => $column,
            'criteria_column' => $criteriaColumn,
            'created_at' => $datetime,
            'updated_at' => $datetime
        ]);
    }
}
