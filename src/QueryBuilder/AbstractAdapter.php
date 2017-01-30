<?php

namespace Deimos\QueryBuilder;

abstract class AbstractAdapter implements Adapter
{

    /**
     * @var \Deimos\Database\Database
     */
    protected $database;

    /**
     * @param \Deimos\Database\Database $database
     */
    public function setConnection(\PDO $database)
    {
        $this->database = $database;
    }

    /**
     * @return \Deimos\Database\Database
     */
    public function connection()
    {
        return $this->database;
    }

}