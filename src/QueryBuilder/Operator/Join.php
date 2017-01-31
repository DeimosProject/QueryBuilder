<?php

namespace Deimos\QueryBuilder\Operator;

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
    public function join(array $table)
    {
        /**
         * @var \Deimos\QueryBuilder\Instruction\Select $select
         */
        $select = $this;
        $join   = new Classes\Join($select, $this->builder, $table);

        return ($this->storageJoin[] = $join);
    }

    /**
     * @return array
     */
    protected function storageJoin()
    {
        return $this->storageJoin;
    }

    /**
     * @param array $storage
     *
     * @return string
     */
    protected function buildJoin($storage)
    {
        $results = [];

        foreach ($storage as $join)
        {
            /**
             * @var Classes\Join $join
             */
            $results[] = (string)$join;
            $this->push($join->attributes());
        }

        return implode(' ', $results);
    }

}