<?php

namespace PhpAbac\Manager;

use PhpAbac\Model\Attribute;

use PhpAbac\Repository\AttributeRepository;

class AttributeManager {
    /** @var AbacManager **/
    protected $abacManager;
    /** @var AttributeRepository **/
    protected $repository;
    
    /**
     * @param AbacManager $abacManager
     */
    public function __construct($abacManager) {
        $this->abacManager = $abacManager;
        
        $this->repository = new AttributeRepository($abacManager->dataManager);
    }
    
    /**
     * @param string $table
     * @param string $column
     * @param string $criteriaColumn
     */
    public function create($table, $column, $criteriaColumn) {
        $this->repository->create($table, $column, $criteriaColumn);
    }
}