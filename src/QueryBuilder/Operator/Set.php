<?php

namespace Deimos\QueryBuilder\Operator;

use Deimos\QueryBuilder\RawQuery;

trait Set
{

    /**
     * @var array
     */
    private $storageSet = [];

    /**
     * @param string $field
     * @param mixed  $value
     *
     * @return static
     */
    public function set($field, $value)
    {
        $this->storageSet[$field] = $value;

        return $this;
    }

    /**
     * @param array $storage
     *
     * @return static
     */
    public function values(array $storage)
    {
        $this->storageSet = $storage;

        return $this;
    }

    /**
     * @return array
     */
    protected function storageSet()
    {
        return $this->storageSet;
    }

    /**
     * @param array $data
     *
     * @return string
     */
    protected function buildSet(array $data)
    {
        $list = [];

        foreach ($data as $field => $value)
        {
            $_value = $value;
            $quote  = true;
            if ($value instanceof RawQuery)
            {
                $_value = (string)$value;
                $quote  = false;
                $this->push($value->attributes());
            }

            if ($quote)
            {
                $_value = $this->builder->adapter()->quote($_value);
            }

            $list[] = $this->builder->adapter()->quote($field) . ' = ' . $_value;
        }

        return implode(', ', $list);
    }

}