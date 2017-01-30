<?php

namespace Deimos\QueryBuilder\Instruction;

use Deimos\QueryBuilder\Instruction;
use Deimos\QueryBuilder\Operator;

class Delete extends Instruction
{

    use Operator\From;
    use Operator\Where;
    use Operator\Limit;

    protected $alias = false;

    /**
     * @return  array
     */
    protected function operators()
    {
        return [
            'from'  => 'DELETE FROM',
            'where' => 'WHERE',
            'limit' => 'LIMIT'
        ];
    }

}