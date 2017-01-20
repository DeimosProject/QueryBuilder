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

    ->groupBy('id')
    ->orderBy('id')

    ->limit(50)
    ->offset(20);

var_dump($query);