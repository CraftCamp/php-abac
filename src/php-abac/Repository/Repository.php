<?php

namespace PhpAbac\Repository;

use PhpAbac\Abac;

abstract class Repository
{
    /** @var \PDO **/
    protected $connection;

    /**
     * @param \PDO $connection
     */
    public function __construct()
    {
        $this->connection = Abac::get('pdo-connection');
    }

    /**
     * Start SQL Transaction
     * Return true in case of success, false otherwise.
     * 
     * @return bool
     */
    public function beginTransaction()
    {
        return $this->connection->beginTransaction();
    }

    /**
     * Commit a SQL Transaction
     * Return true in case of success, false otherwise.
     * 
     * @return bool
     */
    public function commitTransaction()
    {
        return $this->connection->commit();
    }

    /**
     * Rollback a SQL Transaction
     * Return true in case of success, false otherwise.
     * 
     * @return bool
     */
    public function rollbackTransaction()
    {
        return $this->connection->rollback();
    }

    /**
     * @param string $query
     * @param array  $params
     *
     * @return \PDOStatement
     */
    public function query($query, $params = [])
    {
        $statement = $this->connection->prepare($query);
        $statement->execute($params);

        return $statement;
    }

    /**
     * @param string $query
     * @param array  $params
     *
     * @return bool
     */
    public function insert($query, $params = [])
    {
        $statement = $this->connection->prepare($query);

        return $statement->execute($params);
    }
}
