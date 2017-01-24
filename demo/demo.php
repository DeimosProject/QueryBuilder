<?php

include_once dirname(__DIR__) . '/vendor/autoload.php';

$mySQL  = new \Deimos\QueryBuilder\Adapter\MySQL();
$qb     = new Deimos\QueryBuilder\QueryBuilder($mySQL);

$query = $qb->query()
    ->select(
        'id',
        'firstName',
        'lastName',
        ['count' => $qb->raw('COUNT(?)', ['id'])]
    )
    ->from(
        [
            'employee' => 'employees'
        ]
    )
    ->join(['deptOld' => 'departments'])
        ->right()
        ->on('employee.deptOldId', 'deptOld.id')

    ->join(['dept' => 'departments'])
        ->left()
        ->raw('employee.deptId <> dept.id AND dept.id = ?', [5])

    ->where('id', 5) // id = 5 AND
    ->where('id', '>', 5) // id > 5 AND
    ->where('id', '<=', 10) // id <= 10 AND

        // REPLACE AND -> OR
    ->whereOr('id', 13) // OR id = 13 AND

    // REPLACE AND -> OR
    ->whereOr('id', 16) // OR id = 16 AND

    // REPLACE AND -> XOR
    ->whereXor('id', 16) // XOR id = 16 AND

    ->groupBy('id')
    ->orderBy('id')
    ->orderBy('lastName', 'DESC')
    ->orderBy($qb->raw('RAND()'), 'DESC')
    ->orderBy('firstName')

    ->limit(50)
    ->offset(20);

var_dump((string)$query);
var_dump($query->attributes());
var_dump($query);
