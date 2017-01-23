<?php

namespace Deimos\QueryBuilder\Instruction;

use Deimos\QueryBuilder\Instruction;
use Deimos\QueryBuilder\Operator;

class Select extends Instruction
{

    use Operator\Select;
    use Operator\From;
    use Operator\Join;

    use Operator\GroupBy;

    use Operator\Where;
    use Operator\Having;

    use Operator\OrderBy;

    use Operator\Limit;
    use Operator\Offset;

    /**
     * @return array
     */
    protected function &defaults()
    {
        return [
            'select' => '*'
        ];
    }

    /**
     * @return array
     */
    protected function operators()
    {
        return [
            'select'  => 'SELECT',
            'from'    => 'FROM',
            'join'    => '',
            'where'   => 'WHERE',
            'groupBy' => 'GROUP BY',
            'having'  => 'HAVING',
            'orderBy' => 'ORDER BY',
            'limit'   => 'LIMIT',
        ];
    }

}