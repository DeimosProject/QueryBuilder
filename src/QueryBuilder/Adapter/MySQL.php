<?php

namespace Deimos\QueryBuilder\Adapter;

use Deimos\QueryBuilder\Adapter;

class MySQL implements Adapter
{

    /**
     * @return array
     */
    public function listColumns()
    {
        return [];
    }

    /**
     * @return int
     */
    public function insertId()
    {
        return $this->connection()->lastInsertId();
    }

    /**
     * @return \PDO
     */
    public function connection()
    {
        return null;
    }

    /**
     * @return string
     */
    public function dsn()
    {
        return '';
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public function quote($string)
    {
        return '`' . $string . '`';
    }

    /**
     * @return string
     */
    public function name()
    {
        return 'mysql';
    }

    /**
     * @return string
     */
    public function port()
    {
        return 3306;
    }

    /**
     * @return string
     */
    public function host()
    {
        return 'localhost';
    }

}