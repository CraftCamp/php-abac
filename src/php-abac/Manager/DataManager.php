<?php

namespace PhpAbac\Manager;

class DataManager {
    /** @var \PDO **/
    protected $connection;
    
    /**
     * @param \PDO $connection
     */
    public function __construct($connection) {
        $this->connection = $connection;
    }
    
    /**
     * Start SQL Transaction
     * Return true in case of success, false otherwise
     * 
     * @return boolean
     */
    public function beginTransaction() {
        return $this->connection->beginTransaction();
    }
    
    /**
     * Commit a SQL Transaction
     * Return true in case of success, false otherwise
     * 
     * @return boolean
     */
    public function commitTransaction() {
        return $this->connection->commit();
    }
    
    /**
     * Rollback a SQL Transaction
     * Return true in case of success, false otherwise
     * 
     * @return boolean
     */
    public function rollbackTransaction() {
        return $this->connection->rollback();
    }
    
    /**
     * @param string $query
     * @param array $params
     * @return \PDOStatement
     */
    public function fetchQuery($query, $params) {
        $statement = $this->connection->prepare($query);
        $statement->execute($params);
        return $statement;
    }
    
    /**
     * @param string $query
     * @param array $params
     * @return boolean
     */
    public function insertQuery($query, $params) {
        $statement = $this->connection->prepare($query);
        return $statement->execute($params);
    }
}