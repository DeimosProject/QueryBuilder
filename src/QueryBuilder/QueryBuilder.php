<?php

namespace Deimos\QueryBuilder;

use Deimos\QueryBuilder\Exceptions\NotFound;

/**
 * Class QueryBuilder
 *
 * @package Deimos\QueryBuilder
 *
 * @method Instruction\Select query()
 * @method Instruction\Insert create()
 * @method Instruction\Update update()
 * @method Instruction\Delete delete()
 */
class QueryBuilder
{

    /**
     * @var Adapter
     */
    protected $adapter;

    /**
     * @var array
     */
    protected $operators = [
        'query'  => Instruction\Select::class,
        'create' => Instruction\Insert::class,
        'update' => Instruction\Update::class,
        'delete' => Instruction\Delete::class,
    ];

    /**
     * QueryBuilder constructor.
     *
     * @param Adapter $adapter
     */
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param string $name
     * @param array  $options
     *
     * @return mixed
     *
     * @throws NotFound
     */
    public function __call($name, array $options)
    {
        if (!isset($this->operators[$name]))
        {
            throw new NotFound('Operator \'' . $name . '\' not found');
        }

        $class = $this->operators[$name];

        return new $class($this);
    }

    /**
     * @param string $sql
     * @param array  $attributes
     *
     * @return RawQuery
     */
    public function raw($sql, array $attributes = [])
    {
        return new RawQuery($sql, $attributes);
    }

    /**
     * @return Adapter
     */
    public function adapter()
    {
        return $this->adapter;
    }

}