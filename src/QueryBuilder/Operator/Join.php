<?php

namespace Deimos\QueryBuilder\Operator;

use Deimos\QueryBuilder\Instruction\Select;

trait Join
{

    /**
     * @var array
     */
    private $storageJoin = [];

    /**
     * @param array $table
     *
     * @return Classes\Join
     */
    public function join($table)
    {
        /**
         * @var Select $select
         */
        $select = $this;
        $join   = new Classes\Join($select, $table);

        return ($this->storageJoin[] = $join);
    }

    /**
     * @return array
     */
    protected function storageJoin()
    {
        return $this->storageJoin;
    }

}