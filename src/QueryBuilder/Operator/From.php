<?php

namespace Deimos\QueryBuilder\Operator;

trait From
{

    /**
     * @var array
     */
    private $storageFrom = [];

    /**
     * @param array ...$tables
     *
     * @return static
     */
    public function from(...$tables)
    {
        foreach ($tables as $table)
        {
            if (is_string($table))
            {
                $this->storageFrom[$table] = $table;
                continue;
            }

            $key  = key($table);
            $item = current($table); // RawQuery

            $this->storageFrom[$key] = $item;
        }

        return $this;
    }

    /**
     * @return array
     */
    protected function storageFrom()
    {
        return $this->storageFrom;
    }

}