<?php

namespace Deimos\QueryBuilder\Instruction;

use Deimos\QueryBuilder\Instruction;
use Deimos\QueryBuilder\Operator;

/**
 * Class Select
 *
 * @package Deimos\QueryBuilder\Instruction
 *
 * @method $this where($fieldOrStorage, $operatorOrValue = null, $value = null)
 * @method $this whereOr($fieldOrStorage, $operatorOrValue = null, $value = null)
 * @method $this whereXor($fieldOrStorage, $operatorOrValue = null, $value = null)
 *
 * @method $this having($fieldOrStorage, $operatorOrValue = null, $value = null)
 * @method $this havingOr($fieldOrStorage, $operatorOrValue = null, $value = null)
 * @method $this havingXor($fieldOrStorage, $operatorOrValue = null, $value = null)
 */
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
        $_ = [
            'select' => '*'
        ];

        return $_;
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
