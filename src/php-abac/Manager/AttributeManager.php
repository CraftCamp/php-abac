<?php

namespace PhpAbac\Manager;

use PhpAbac\Repository\AttributeRepository;

use PhpAbac\Model\AbstractAttribute;
use PhpAbac\Model\Attribute;
use PhpAbac\Model\EnvironmentAttribute;

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
     * @param AbstractAttribute $attribute
     * @param string $attributeType
     * @param int $userId
     * @param int $objectId
     */
    public function retrieveAttribute(AbstractAttribute $attribute, $attributeType, $userId, $objectId) {
        // The switch is important.
        // Even if we call the same method for the two first cases,
        // the given argument isn't the same.
        switch($attributeType) {
            case 'user':
                $this->repository->retrieveAttribute($attribute, $userId);
                break;
            case 'object':
                $this->repository->retrieveAttribute($attribute, $objectId);
                break;
            case 'environment':
                $attribute->setValue(getenv($attribute->getVariableName()));
                break;
        }
    }
}