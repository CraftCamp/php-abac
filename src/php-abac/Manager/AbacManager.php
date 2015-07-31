<?php

namespace PhpAbac\Manager;

class AbacManager {
    /** @var DataManager **/
    private $dataManager;
    /** @var PolicyRuleManager **/
    private $policyRuleManager;
    /** @var AttributeManager **/
    private $attributeManager;
    
    /**
     * @param \PDO $connection
     */
    public function __construct($connection)
    {
        $this->dataManager = new DataManager($connection);
        $this->policyRuleManager = new PolicyRuleManager($this);
        $this->attributeManager = new AttributeManager($this);
    }
    
    /**
     * @return DataManager
     */
    public function getDataManager() {
        return $this->dataManager;
    }
    
    /**
     * @return AttributeManager
     */
    public function getPolicyRuleManager() {
        return $this->policyRuleManager;
    }
    
    /**
     * @return AttributeManager
     */
    public function getAttributeManager() {
        return $this->attributeManager;
    }
}