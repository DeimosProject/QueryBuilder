<?php

namespace Deimos\QueryBuilder;

class RawQuery
{

    /**
     * @var string
     */
    protected $sql;

    /**
     * @var array
     */
    protected $attributes;

    /**
     * RawQuery constructor.
     *
     * @param string $sql
     * @param array  $attributes
     */
    public function __construct($sql, array $attributes = [])
    {
        $this->sql        = $sql;
        $this->attributes = $attributes;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->sql;
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return $this->attributes;
    }

}