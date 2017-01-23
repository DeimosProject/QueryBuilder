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

    protected function buildLimit($limit)
    {
        if (method_exists($this, 'storageOffset'))
        {
            $offset = $this->storageOffset();

            if ($limit === null)
            {
                if ($offset === null)
                {
                    return '';
                }

                $limit = '18446744073709551615';
            }

            return $offset . ', ' . $limit;
        }

        return $this->storageLimit();
    }

}