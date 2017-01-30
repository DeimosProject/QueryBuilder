<?php

include_once dirname(__DIR__) . '/vendor/autoload.php';

$mySQL = new \Deimos\QueryBuilder\Adapter\MySQL();
$qb    = new Deimos\QueryBuilder\QueryBuilder($mySQL);

$query = $qb->delete()
    ->from('from')
    ->where('id', 5)
    ->whereXor('id', 5)
    ->where([
        'OR' => [
            ['id', 9],
            ['id', 6],
        ]
    ])
    ->whereOr('id', 5)
    ->where('id', 5);

var_dump((string)$query); // build Query

var_dump($query);
var_dump($query->attributes());
