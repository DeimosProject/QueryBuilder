<?php

namespace Deimos\QueryBuilder\Operator\Classes;

use Deimos\QueryBuilder\Instruction\Select;
use Deimos\QueryBuilder\QueryBuilder;
use Deimos\QueryBuilder\RawQuery;

class Join
{

    /**
     * @var array
     */
    protected $table;

    /**
     * @var QueryBuilder
     */
    protected $builder;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var Select
     */
    protected $select;

    /**
     * @var string|RawQuery
     */
    protected $query;

    /**
     * Join constructor.
     *
     * @param Select       $select
     * @param QueryBuilder $builder
     * @param array        $table
     */
    public function __construct($select, QueryBuilder $builder, array $table)
    {
        $this->table   = $table;
        $this->builder = $builder;
        $this->select  = $select;
    }

    /**
     * @return static
     */
    public function left()
    {
        return $this->type('LEFT');
    }

    /**
     * @param string $value
     *
     * @return static
     */
    public function type($value)
    {
        $this->type = $value;

        return $this;
    }

    /**
     * @return static
     */
    public function right()
    {
        return $this->type('RIGHT');
    }

    /**
     * @return static
     */
    public function inner()
    {
        return $this->type('INNER');
    }

    /**
     * @param string $first
     * @param string $second
     *
     * @return Select
     */
    public function on($first, $second)
    {
        return $this->raw($first . ' = ' . $second);
    }

    /**
     * @param string $query
     * @param array  $attributes
     *
     * @return Select
     */
    public function raw($query, array $attributes = [])
    {
        $this->query = new RawQuery($query, $attributes);

        return $this->select;
    }

    public function attributes()
    {
        $table = current($this->table);

        if ($table instanceof RawQuery || $table instanceof Select)
        {
            return array_merge(
                $table->attributes(),
                $this->query->attributes()
            );
        }

        return $this->query->attributes();
    }

    public function __toString()
    {
        $table = $this->getTable();
        $alias = key($table);

        return $this->type . ' JOIN ' .
            $this->builder->adapter()->quote($table[$alias]) . ' AS ' .
            $this->builder->adapter()->quote($alias) . ' ON ' .
            $this->query;

    }

    /**
     * @return array
     */
    public function getTable()
    {
        return $this->table;
    }

}