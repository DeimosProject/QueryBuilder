<?php

namespace Deimos\QueryBuilder\Adapter;

use Deimos\QueryBuilder\AbstractAdapter;

class SQLite extends AbstractAdapter
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @return string
     */
    public function dsn()
    {
        return sprintf(
            '%s:%s',
            $this->name(),
            $this->path()
        );
    }

    /**
     * @param $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function path()
    {
        return $this->path;
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
        return '"' . $string . '"';
    }

    /**
     * @return string
     */
    public function name()
    {
        return 'sqlite';
    }

}