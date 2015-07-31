<?php

use PhpAbac\Repository\AttributeRepository;

class AttributeManager {
    /** @var AttributeRepository **/
    protected $repository;
    
    /**
     * @param AbacManager $abacManager
     */
    public function __construct() {
        $this->repository = new AttributeRepository();
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