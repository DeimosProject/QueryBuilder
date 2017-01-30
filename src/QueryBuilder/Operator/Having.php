<?php

namespace Deimos\QueryBuilder\Operator;

use Deimos\QueryBuilder\RawQuery;

/**
 * Class Having
 *
 * @package Deimos\QueryBuilder\Operator
 */
trait Having
{

    /**
     * @var array
     */
    private $storageHaving = [];

    /**
     * @param array ...$fields
     *
     * @return static
     */
    public function having(...$fields)
    {
        return $this->_having('AND', $fields);
    }

    /**
     * @param string $operator
     * @param array  ...$fields
     *
     * @return $this
     */
    private function _having($operator, array $fields)
    {
        $count = count($fields);

        if ($count < 2 || $count > 3)
        {
            $fields = var_export($fields, true);
            throw new \InvalidArgumentException($fields);
        }

        $this->storageHaving[] = [$operator => $fields];

        return $this;
    }

    /**
     * @param array ...$fields
     *
     * @return static
     */
    public function havingOr(...$fields)
    {
        return $this->_having('OR', $fields);
    }

    /**
     * @param array ...$fields
     *
     * @return static
     */
    public function havingXor(...$fields)
    {
        return $this->_having('XOR', $fields);
    }

    /**
     * @return array
     */
    protected function storageHaving()
    {
        return $this->storageHaving;
    }

    /**
     * @param $storage
     *
     * @return string
     */
    protected function buildHaving($storage)
    {
        /**
         * @var array $where
         */
        $having = $this->buildWhereOperator($storage);

        $sql                 = '';
        $this->allowOperator = false;
        $this->buildIf2String($having, $sql);

        return $sql;
    }

}