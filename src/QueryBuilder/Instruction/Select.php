<?php

namespace Deimos\QueryBuilder\Instruction;

use Deimos\QueryBuilder\Instruction;
use Deimos\QueryBuilder\Operator;

class Select extends Instruction
{

    use Operator\Select;
    use Operator\From;
    use Operator\Join;

    use Operator\Limit;
    use Operator\Offset;

    public function __toString()
    {
        return __METHOD__;
    }

}