<?php

namespace Deimos\QueryBuilder\Operator;

trait Limit
{

    /**
     * @var int
     */
    private $storageLimit;

    /**
     * @param int $value
     *
     * @return static
     */
    public function limit($value)
    {
        $this->storageLimit = $value;

        return $this;
    }

    /**
     * @return int
     */
    protected function storageLimit()
    {
        return $this->storageLimit;
    }

}