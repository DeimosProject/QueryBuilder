<?php

namespace Deimos\QueryBuilder\Instruction;

use Deimos\QueryBuilder\Instruction;
use Deimos\QueryBuilder\Operator;

class Update extends Instruction
{

    use Operator\From;
    use Operator\Set;
    use Operator\Where;
    use Operator\OrderBy;
    use Operator\Limit;

    protected $alias = false;

    /**
     * @return array
     */
    protected function operators()
    {
        return [
            'from'    => 'UPDATE',
            'set'     => 'SET',
            'where'   => 'WHERE',
            'orderBy' => 'ORDER BY',
            'limit'   => 'LIMIT'
        ];
    }

}