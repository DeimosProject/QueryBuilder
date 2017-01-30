<?php

include_once dirname(__DIR__) . '/vendor/autoload.php';

$mySQL  = new \Deimos\QueryBuilder\Adapter\MySQL();
$qb     = new Deimos\QueryBuilder\QueryBuilder($mySQL);

$query = $qb->create()
    ->from('from')
    ->value('hello', 'world')
    ->value(
        'id',
        $qb->query()->select(['id' => $qb->raw('max(id)')])->from('select')
    )->value(
        'index',
        $qb->raw(5)
    );

var_dump((string)$query); // build Query

var_dump($query);
var_dump($query->attributes());
