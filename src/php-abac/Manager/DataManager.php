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
    
    public function fetchQuery($query, $params) {
        $statement = $this->connection->prepare($query);
        $statement->execute($params);
        return $statement;
    }
}