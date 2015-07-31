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