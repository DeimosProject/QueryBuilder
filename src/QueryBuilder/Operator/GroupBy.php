<?php

namespace Deimos\QueryBuilder\Operator;

trait GroupBy
{

    /**
     * @var array
     */
    private $storageGroupBy = [];

    /**
     * @param string $field
     *
     * @return static
     */
    public function groupBy($field)
    {
        $this->storageGroupBy[] = $field;

        return $this;
    }

    /**
     * @return array
     */
    protected function storageGroupBy()
    {
        return $this->storageGroupBy;
    }

}