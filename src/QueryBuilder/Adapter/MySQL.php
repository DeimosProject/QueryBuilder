<?php

namespace Deimos\QueryBuilder\Adapter;

use Deimos\QueryBuilder\AbstractAdapter;

class MySQL extends AbstractAdapter
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