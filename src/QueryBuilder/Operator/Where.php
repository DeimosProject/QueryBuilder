<?php

namespace Deimos\QueryBuilder\Operator;

/**
 * Class Where
 *
 * @package Deimos\QueryBuilder\Operator
 */
trait Where
{

    /**
     * @var array
     */
    private $storageWhere = [];

    /**
     * @param string $operator
     * @param array  ...$fields
     *
     * @return $this
     */
    private function _where($operator, array $fields)
    {
        $count = count($fields);

        if ($count < 2 || $count > 3)
        {
            $fields = var_export($fields, true);
            throw new \InvalidArgumentException($fields);
        }

        $this->storageWhere[] = [$operator => $fields];

        return $this;
    }

    /**
     * @param array ...$fields
     *
     * @return static
     */
    public function where(...$fields)
    {
        return $this->_where('AND', $fields);
    }

    /**
     * @param array ...$fields
     *
     * @return static
     */
    public function whereOr(...$fields)
    {
        return $this->_where('OR', $fields);
    }

    /**
     * @param array ...$fields
     *
     * @return static
     */
    public function whereXor(...$fields)
    {
        return $this->_where('XOR', $fields);
    }

    /**
     * @return array
     */
    protected function storageWhere()
    {
        return $this->storageWhere;
    }

}