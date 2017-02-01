<?php

namespace Deimos\QueryBuilder\Operator;

use Deimos\QueryBuilder\Exceptions\NotFound;

trait Select
{

    /**
     * @var array
     */
    private $storageSelect = [];

    /**
     * @param array $fields
     *
     * @return static
     */
    public function setSelect(array $fields)
    {
        $this->storageSelect = [];

        foreach ($fields as $key => $field)
        {
            $this->select([$key => $field]);
        }

        return $this;
    }

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

            if (is_object($field))
            {
                throw new NotFound('Alias for parameter `' . $field . '` not found');
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