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
            'SELECT table, column FROM abac_attributes WHERE id = :id'
        , ['id' => $attributeId]);
        $data = $statement->fetch();
        
        return
            (new Attribute())
            ->setColumn($data['column'])
            ->setTable($data['table'])
        ;
    }
    
    /**
     * @param Attribute &$attribute
     * @param integer $id
     */
    public function retrieveAttribute(Attribute &$attribute, $id) {
        $statement = $this->dataManager->fetchQuery(
            'SELECT :column FROM :table WHERE id = :id'
        , [
            'column' => $attribute->getColumn(),
            'table' => $attribute->getTable(),
            'id' => $id
        ]);
        $data = $statement->fetch();
        $attribute->setValue($data[$attribute->getColumn()]);
    }
}
