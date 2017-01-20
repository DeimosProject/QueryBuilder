<?php

namespace Deimos\QueryBuilder;

abstract class Instruction
{

    /**
     * @var QueryBuilder
     */
    protected $builder;

    /**
     * @var Adapter
     */
    protected $adapter;

    /**
     * Instruction constructor.
     *
     * @param QueryBuilder $builder
     * @param Adapter      $adapter
     */
    public function __construct(QueryBuilder $builder, Adapter $adapter)
    {
        $this->builder = $builder;
        $this->adapter = $adapter;
    }

    abstract public function __toString();

}