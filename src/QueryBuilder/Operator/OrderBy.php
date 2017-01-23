<?php

namespace Deimos\QueryBuilder\Operator;

use Deimos\QueryBuilder\RawQuery;

trait OrderBy
{

    /**
     * @var array
     */
    private $storageOrderBy = [];

    /**
     * @param RawQuery|string $field
     * @param string          $direction
     *
     * @return static
     */
    public function orderBy($field, $direction = 'ASC')
    {
        $this->storageOrderBy[] = [$field, $direction];

        return $this;
    }

    /**
     * @return array
     */
    protected function storageOrderBy()
    {
        return $this->storageOrderBy;
    }

    protected function buildOrderBy()
    {
        $list = [];

        foreach ($this->storageOrderBy as $item)
        {
            $field = $item[0];

            if ($field instanceof RawQuery)
            {
                $this->push($field->attributes());
            }
            else
            {
                $field = $this->builder->adapter()->quote($field);
            }

            $list[] = $field . ' ' . $item[1];
        }

        return implode(', ', $list);
    }

}