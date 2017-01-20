<?php

namespace Deimos\QueryBuilder\Operator;

trait Offset
{

    /**
     * @var int
     */
    private $storageOffset;

    /**
     * @param int $value
     *
     * @return static
     */
    public function offset($value)
    {
        $this->storageOffset = $value;

        return $this;
    }

    /**
     * @return int
     */
    protected function storageOffset()
    {
        return $this->storageOffset;
    }

}