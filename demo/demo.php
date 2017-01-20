<?php

include_once dirname(__DIR__) . '/vendor/autoload.php';

use Deimos\QueryBuilder\RawQuery;

$mySQL        = new \Deimos\QueryBuilder\Adapter\MySQL();
$queryBuilder = new Deimos\QueryBuilder\QueryBuilder($mySQL);

$query = $queryBuilder->query()
    ->select(
        'id',
        'firstName',
        'lastName',
        ['count' => (new RawQuery('COUNT(?)', ['id']))]
    )
    ->from(
        [
            'employee' => 'employees'
        ]
    )
    ->join(['deptOld' => 'departments'])
        ->on('employee.deptOldId = deptOld.id')

    ->join(['dept' => 'departments'])
        ->on('employee.deptId = dept.id')

    ->where('id', 5) // id = 5 AND
    ->where('id', '>', 5) // id > 5 AND
    ->where('id', '<=', 10) // id <= 10 AND

        // REPLACE AND -> OR
    ->whereOr('id', 13) // OR id = 13 AND

        // REPLACE AND -> OR
    ->whereOr('id', 16) // OR id = 16 AND

    ->groupBy('id')
    ->orderBy('id')

    ->limit(50)
    ->offset(20);

var_dump($query);