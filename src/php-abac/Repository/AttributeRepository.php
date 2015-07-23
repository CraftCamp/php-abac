<?php

namespace PhpAbac\Repository;

use PhpAbac\Manager\DataManager;

use PhpAbac\Model\Attribute;

class AttributeRepository {
    /** @var DataManager **/
    private $dataManager;
    
    /**
     * @param DataManager $dataManager
     */
    public function __construct($dataManager) {
        $this->dataManager = $dataManager;
    }
    
    /**
     * @param integer $attributeId
     * @return Attribute
     */
    public function findAttribute($attributeId) {
        $statement = $this->dataManager->fetchQuery(
            'SELECT table, column, column_id FROM abac_attributes WHERE id = :id'
        , ['id' => $attributeId]);
        $data = $statement->fetch();
        
        return
            (new Attribute())
            ->setTable($data['table'])
            ->setColumn($data['column'])
            ->setIdColumn($data['id_column'])
        ;
    }
    
    /**
     * @param Attribute &$attribute
     * @param integer $id
     */
    public function retrieveAttribute(Attribute &$attribute, $id) {
        $statement = $this->dataManager->fetchQuery(
            'SELECT :column FROM :table WHERE :id_column = :id'
        , [
            'column' => $attribute->getColumn(),
            'table' => $attribute->getTable(),
            'id' => $id,
            'column_id' => $attribute->getIdColumn()
        ]);
        $data = $statement->fetch();
        $attribute->setValue($data[$attribute->getColumn()]);
    }
}
