<?php

namespace Deimos\QueryBuilder\Operator;

use Deimos\QueryBuilder\Instruction\Select;
use Deimos\QueryBuilder\RawQuery;

trait Values
{

    /**
     * @var array
     */
    private $storageValues = [];

    /**
     * @param string $field
     * @param mixed  $value
     *
     * @return static
     */
    public function value($field, $value)
    {
        $this->storageValues[$field] = $value;

        return $this;
    }

    /**
     * @param array $storage
     *
     * @return static
     */
    public function values(array $storage)
    {
        $this->storageValues = $storage;

        return $this;
    }

    /**
     * @return array
     */
    protected function storageValues()
    {
        return $this->storageValues;
    }

    /**
     * @param array $data
     *
     * @return string
     */
    protected function buildValues(array $data)
    {
        $values = [];

        foreach ($data as $field => $value)
        {
            $_value = $value;

            $field = $this->builder->adapter()->quote($field);

            $values[$field] = '?';

            if ($value instanceof Select || $value instanceof RawQuery)
            {
                $values[$field] = '(' . $value . ')';
                $this->push($value->attributes());
            }
            else
            {
                $this->push([$_value]);
            }
        }

        return '(' .
            implode(', ', array_keys($values)) .
            ') VALUES (' .
            implode(', ', $values) .
            ')';
    }

}