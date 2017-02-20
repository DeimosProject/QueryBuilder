<?php

namespace Deimos\QueryBuilder\Adapter;

use Deimos\QueryBuilder\AbstractAdapter;

class PostgreSQL extends AbstractAdapter
{

    protected $port = 5432;

    public function dsn()
    {
        return sprintf(
            '%s:host=%s;port=%s;dbname=%s',
            $this->name(),
            $this->host(),
            $this->port(),
            $this->dbName()
        );
    }

    /**
     * @return array
     */
    public function columns()
    {
        return [];
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public function quote($string)
    {
        return '"' . str_replace('.', '"."', $string) . '"';
    }

    /**
     * @return string
     */
    public function name()
    {
        return 'pgsql';
    }

}
