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

    protected function buildLimit($limit)
    {
        if (method_exists($this, 'storageOffset'))
        {
            $offset = $this->storageOffset();

            return ($offset ? $offset . ', ' : '') . $limit;
        }

        return $limit;
    }

    /**
     * @return int
     */
    protected function storageLimit()
    {
        return $this->storageLimit;
    }

}
