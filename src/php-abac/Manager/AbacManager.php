<?php

namespace PhpAbac\Manager;

class AbacManager {
    /** @var DataManager **/
    private $dataManager;
    /** @var AttributeManager **/
    private $attributeManager;
    
    /**
     * @param \PDO $connection
     */
    public function __construct($connection)
    {
        $this->dataManager = new DataManager($connection);
        $this->attributeManager = new AttributeManager();
    }
}