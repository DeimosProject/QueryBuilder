<?php

namespace Deimos\QueryBuilder\Instruction;

use Deimos\QueryBuilder\Instruction;
use Deimos\QueryBuilder\Operator;

class Insert extends Instruction
{

    use Operator\From;
    use Operator\Set;

    /**
     * @return  array
     */
    protected function operators()
    {
        return [
            'from'    => 'INSERT',
            'set'     => 'SET',
            'where'   => 'WHERE',
            'orderBy' => 'ORDER BY',
            'limit'   => 'LIMIT'
        ];
    }

}