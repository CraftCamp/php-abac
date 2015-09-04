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
            'SELECT ad.name, a.table_name, a.column_name, a.criteria_column, ad.created_at, ad.updated_at ' .
            'FROM abac_attributes_data ad INNER JOIN abac_attributes a ON a.id = ad.id WHERE ad.id = :id'
        , ['id' => $attributeId]);
        $data = $statement->fetch();
        
        return
            (new Attribute())
            ->setName($data['name'])
            ->setTable($data['table_name'])
            ->setColumn($data['column_name'])
            ->setCriteriaColumn($data['criteria_column'])
            ->setCreatedAt($data['created_at'])
            ->setUpdatedAt($data['updated_at'])
        ;
    }
    
    /**
     * @param Attribute &$attribute
     * @param mixed $criteria
     */
    public function retrieveAttribute(Attribute $attribute, $criteria) {
        $statement = $this->query(
            "SELECT {$attribute->getColumn()} FROM {$attribute->getTable()} WHERE {$attribute->getCriteriaColumn()} = {$criteria}"
        );
        $data = $statement->fetch();
        $attribute->setValue($data[$attribute->getColumn()]);
    }
    
    /**
     * @param string $name
     * @param string $table
     * @param string $column
     * @param string $criteriaColumn
     * @return Attribute
     */
    public function createAttribute($name, $table, $column, $criteriaColumn) {
        $datetime = new \DateTime();
        $formattedDatetime = $datetime->format('Y-m-d H:i:s');
        
        $this->insert(
            'INSERT INTO abac_attributes_data (created_at, updated_at, name) ' .
            'VALUES(:created_at, :updated_at, :name);' .
            'INSERT INTO abac_attributes (id, column_name, criteria_column) ' .
            'VALUES(LAST_INSERT_ID(), :table_name, :column_name, :criteria_column);'
        , [
            'name' => $name,
            'table_name' => $table,
            'column_name' => $column,
            'criteria_column' => $criteriaColumn,
            'created_at' => $formattedDatetime,
            'updated_at' => $formattedDatetime
        ]);
        
        return
            (new Attribute())
            ->setId($this->connection->lastInsertId('abac_attributes'))
            ->setName($name)
            ->setTable($table)
            ->setColumn($column)
            ->setCriteriaColumn($criteriaColumn)
            ->setCreatedAt($datetime)
            ->setUpdatedAt($datetime)
        ;
    }
}
