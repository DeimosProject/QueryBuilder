<?php

namespace Deimos\QueryBuilder;

abstract class Instruction
{

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @var QueryBuilder
     */
    protected $builder;

    /**
     * Instruction constructor.
     *
     * @param QueryBuilder $builder
     */
    public function __construct(QueryBuilder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @param string $name
     *
     * @return array
     */
    public function getStorage($name)
    {
        $nameStorage = 'storage' . ucfirst($name);
        $data        = $this->{$nameStorage}();
        $defaults    = &$this->defaults();

        if (empty($data))
        {
            if (isset($defaults[$name]))
            {
                return $defaults[$name];
            }

            return null;
        }

        return $data;
    }

    /**
     * @param array $attributes
     */
    protected function push(array $attributes)
    {
        $this->attributes = array_merge($this->attributes, $attributes);
    }

    /**
     * @param $value
     * @param $quote
     */
    protected function buildRAW(&$value, &$quote)
    {

        if ($value instanceof RawQuery || $value instanceof self)
        {
            $this->push($value->attributes());
            $quote = false;

            $value = '(' . (string)$value . ')';
        }

    }

    /**
     * @param string $name
     * @param mixed  $storage
     *
     * @return string
     */
    protected function build($name, $storage)
    {
        if ($storage !== null)
        {
            $method = 'build' . ucfirst($name);

            if (method_exists($this, $method))
            {
                return $this->{$method}($storage);
            }

            if (is_array($storage))
            {
                $result = [];
                foreach ($storage as $key => $value)
                {
                    $quote = true;
                    $this->buildRAW($value, $quote);

                    if ($quote)
                    {
                        $value = $this->builder->adapter()->quote($value);
                    }

                    $result[] = $value .
                        (!is_int($key) ? ' AS ' . $this->builder->adapter()->quote($key) : '');
                }

                return implode(', ', $result);
            }
        }

        return $storage;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $this->attributes = [];
        $sql              = [];

        foreach ($this->operators() as $operator => $strict)
        {
            $data  = $this->getStorage($operator);
            $build = $this->build($operator, $data);

            if (!empty($build))
            {
                $sql[] = $strict . ' ' . $build;
            }
        }

        return implode("\n", $sql);
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return $this->attributes;
    }

    /**
     * @return array
     */
    protected function &defaults()
    {
        return [];
    }

    /**
     * @return array
     */
    abstract protected function operators();

}