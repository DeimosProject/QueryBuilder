<?php

namespace Deimos\QueryBuilder\Instruction;

use Deimos\QueryBuilder\Instruction;
use Deimos\QueryBuilder\Operator;

class Insert extends Instruction
{

    use Operator\From;
    use Operator\Values;

    protected $alias = false;

    /**
     * @return  array
     */
    protected function operators()
    {
        return [
            'from'       => 'INSERT INTO',
            'values'     => '',
        ];
    }

}