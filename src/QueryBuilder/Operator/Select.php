<?php

namespace Deimos\QueryBuilder\Operator;

trait Select
{

    /**
     * @var array
     */
    private $storageSelect = [];

    /**
     * @param array ...$fields
     *
     * @return static
     */
    public function select(...$fields)
    {
        foreach ($fields as $field)
        {
            if (is_string($field))
            {
                $this->storageSelect[$field] = $field;
                continue;
            }

            $key  = key($field);
            $item = current($field);

            $this->storageSelect[$key] = $item;
        }

        return $this;
    }

    /**
     * @return array
     */
    protected function storageSelect()
    {
        return $this->storageSelect;
    }

}