<?php

namespace PhpAbac\Manager;

use PhpAbac\Repository\AttributeRepository;

use PhpAbac\Model\Attribute;

class AttributeManager {
    /** @var AttributeRepository **/
    protected $repository;
    
    public function __construct() {
        $this->repository = new AttributeRepository();
    }
    
    /**
     * @param string $name
     * @param string $table
     * @param string $column
     * @param string $criteriaColumn
     * @return Attribute
     */
    public function create($name, $table, $column, $criteriaColumn) {
        return $this->repository->createAttribute($name, $table, $column, $criteriaColumn);
    }
    
    /**
     * @param Attribute $attribute
     * @param string $attributeType
     * @param int $userId
     * @param int $objectId
     */
    public function retrieveAttribute(Attribute $attribute, $attributeType, $userId, $objectId) {
        switch($attributeType) {
            case 'user':
                $this->repository->retrieveAttribute($attribute, $userId);
                break;
            case 'object':
                $this->repository->retrieveAttribute($attribute, $objectId);
                break;
            case 'environment':
                break;
        }
    }
}