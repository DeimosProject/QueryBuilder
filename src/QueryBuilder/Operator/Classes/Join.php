<?php

namespace Deimos\QueryBuilder\Operator\Classes;

use Deimos\QueryBuilder\Instruction\Select;
use Deimos\QueryBuilder\RawQuery;

class Join
{

    /**
     * @var array
     */
    protected $table;

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
     * @param Select $select
     * @param array  $table
     */
    public function __construct($select, array $table)
    {
        $this->table  = $table;
        $this->select = $select;
    }

    /**
     * @param string|RawQuery $query
     *
     * @return Select
     */
    public function on($query)
    {
        $this->query = $query;

        return $this->select;
    }

    /**
     * @return array
     */
    public function getTable()
    {
        return $this->table;
    }

}