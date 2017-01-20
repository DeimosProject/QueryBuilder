<?php

namespace Deimos\QueryBuilder;

use Deimos\QueryBuilder\Exceptions\NotFound;

/**
 * Class QueryBuilder
 *
 * @package Deimos\QueryBuilder
 *
 * @method Instruction\Select query()
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
        'query' => Instruction\Select::class,
//        'create'  => Operator\Insert::class,
//        'update'  => Operator\Update::class,
//        'replace' => Operator\Replace::class,
//        'delete'  => Operator\Delete::class,
//        'drop'    => Operator\Drop::class,
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

        return new $class($this, $this->adapter);
    }

}