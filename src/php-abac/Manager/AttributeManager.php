<?php

namespace PhpAbac\Manager;

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
}