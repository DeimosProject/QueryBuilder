<?php

namespace Deimos\QueryBuilder\Instruction;

use Deimos\QueryBuilder\Instruction;
use Deimos\QueryBuilder\Operator;

/**
 * Class Delete
 *
 * @package Deimos\QueryBuilder\Instruction
 *
 * @method $this where($field, $operatorOrValue, $value = null)
 * @method $this whereOr($field, $operatorOrValue, $value = null)
 * @method $this whereXor($field, $operatorOrValue, $value = null)
 */
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