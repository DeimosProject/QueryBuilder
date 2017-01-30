<?php

namespace Deimos\QueryBuilder\Adapter;

use Deimos\QueryBuilder\AbstractAdapter;

class MySQL extends AbstractAdapter
{

    protected $port = 3306;

    public function dsn()
    {
        return sprintf(
            '%s:host=%s;port=%s;dbname=%s',
            $this->name(),
            $this->host(),
            $this->port(),
            $this->database()
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
        return '`' . $string . '`';
    }

    /**
     * @return string
     */
    public function name()
    {
        return 'mysql';
    }

}