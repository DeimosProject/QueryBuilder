<?php

namespace Deimos\QueryBuilder\Operator;

use Deimos\QueryBuilder\Instruction\Select;
use Deimos\QueryBuilder\RawQuery;

/**
 * Class Where
 *
 * @package Deimos\QueryBuilder\Operator
 */
trait Where
{

    protected $allowOperator;
    /**
     * @var array
     */
    private $storageWhere = [];

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
     * @param string $operator
     * @param array  ...$fields
     *
     * @return $this
     */
    private function _where($operator, array $fields)
    {
        $count = count($fields);

        if ($count < 1 || $count > 3)
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

    /**
     * @param array ...$args
     *
     * @return string
     */
    protected function buildWhereOne(...$args)
    {
        $equal = count($args) === 3;
        $opr   = $equal ? $args[1] : '=';

        $_value = $args[1 + $equal];
        $raw    = false;

        if ($args[1 + $equal] instanceof RawQuery || $args[1 + $equal] instanceof Select)
        {
            $_value = '(' . (string)$args[1 + $equal] . ')';
            $raw    = true;
            $this->push($args[1 + $equal]->attributes());
        }

        if ($raw)
        {
            $value = $_value;
        }
        else
        {
            $value = '?';
            if (is_array($_value))
            {
                $value = str_repeat('?, ', count($_value));
                $value = '(' . rtrim($value, ', ') . ')';
                $this->push($_value);
            }
            else
            {
                $this->push([$_value]);
            }
        }

        $list[] = $this->builder->adapter()->quote($args[0]) . ' ' . $opr . ' ' . $value;

        return implode(' ', $list);
    }

    protected function buildWhere($storage)
    {
        /**
         * @var array $where
         */
        $where = $this->buildWhereOperator($storage);

        // fixme: $where = [$where];
        if (!is_array($where[0]))
        {
            $where = [$where];
        }

        $sql                 = '';
        $this->allowOperator = false;
        $this->buildIf2String($where, $sql);

        return $sql;
    }

    /**
     * @param array  $args
     * @param string $defaultOperator
     *
     * @return string
     */
    protected function buildWhereOperator(array $args, $defaultOperator = 'AND')
    {
        $storage  = [];
        $key      = key($args);
        $operator = is_string($key) ? $key : $defaultOperator;

        foreach ($args as $arg)
        {
            $isArray = is_array(current($arg));
            if ($isArray)
            {
                $storage[] = $this->buildWhereOperator($arg, $operator);
            }
            else
            {
                $storage[] = [
                    $operator,
                    call_user_func_array([$this, 'buildWhereOne'], $arg)
                ];
            }
        }

        if (count($storage) === 1)
        {
            return current($storage);
        }

        return $storage;
    }

    /**
     * @param array  $storage
     * @param string $toStorage
     */
    protected function buildIf2String(array $storage, &$toStorage)
    {
        $toStorage .= '(';
        $lastOperator = '';

        foreach ($storage as $key => $value)
        {
            if (is_string($value[0]))
            {
                $this->allowOperator = true;
                $lastOperator        = $value[0];
                if ($key)
                {
                    $toStorage .= ' ' . $lastOperator . ' ';
                }

                $toStorage .= ' (' . $value[1] . ') ';
            }
            else
            {
                if ($this->allowOperator)
                {
                    $toStorage .= ' ' . $lastOperator . ' ';
                }

                $this->buildIf2String($value, $toStorage);
            }
        }

        $toStorage .= ')';
    }

}