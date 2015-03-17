<?php
namespace Mindweb\LogMysql\Repository;

use Doctrine\DBAL\Connection;
use Mindweb\Db;
use Mindweb\Log;

class LogRepository implements Db\Repository, Log\Repository\LogRepository
{
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param int $id
     */
    public function markAsProcessed($id)
    {
        $this->connection->update(
            'logs',
            array('processed' => '1'),
            array('id' => $id)
        );
    }

    /**
     * @param int $limit
     * @return array
     */
    public function fetchAllForLimit($limit)
    {
        return $this->connection->fetchAll(
            'SELECT * FROM logs WHERE processed IS NULL LIMIT ' . $limit
        );
    }

    /**
     * @param string $actionTime
     * @param array $values
     */
    public function insert($actionTime, $values)
    {
        $this->connection->executeQuery(
            'INSERT INTO logs VALUES(NULL, ?, NULL, ?, ?, ?)',
            array_merge(
                array($actionTime),
                $values
            )
        );
    }
}