<?php

namespace PhpAbac\Manager;

use PhpAbac\Repository\AttributeRepository;

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
     */
    public function create($name, $table, $column, $criteriaColumn) {
        $this->repository->create($name, $table, $column, $criteriaColumn);
    }
}