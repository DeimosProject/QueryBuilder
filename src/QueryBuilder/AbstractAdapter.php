<?php

namespace Deimos\QueryBuilder;

abstract class AbstractAdapter implements Adapter
{

    /**
     * @var string
     */
    protected $host = 'localhost';

    /**
     * @var string
     */
    protected $port;

    /**
     * @var string
     */
    protected $dbName;

    /**
     * @var \Deimos\Database\Database
     */
    protected $database;

    /**
     * @param \Deimos\Database\Database $database
     *
     * @return static
     */
    public function setConnection(\PDO $database)
    {
        $this->database = $database;

        return $this;
    }

    /**
     * @param string $host
     *
     * @return static
     */
    public function setHost($host)
    {
        if ($host)
        {
            $this->host = $host;
        }

        return $this;
    }

    /**
     * @param string $port
     *
     * @return static
     */
    public function setPort($port)
    {
        if ($port)
        {
            $this->port = $port;
        }

        return $this;
    }

    /**
     * @param string $dbName
     *
     * @return static
     */
    public function setDbName($dbName)
    {
        if ($dbName)
        {
            $this->dbName = $dbName;
        }

        return $this;
    }

    /**
     * @return int
     */
    public function insertId()
    {
        return $this
            ->database()
            ->connection()
            ->lastInsertId();
    }

    /**
     * @return \Deimos\Database\Database
     */
    public function database()
    {
        return $this->database;
    }

    /**
     * @return string
     */
    public function dbName()
    {
        return $this->dbName;
    }

    /**
     * @return string
     */
    public function port()
    {
        return $this->port;
    }

    /**
     * @return string
     */
    public function host()
    {
        return $this->host;
    }

}