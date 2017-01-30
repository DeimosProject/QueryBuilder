<?php

include_once dirname(__DIR__) . '/vendor/autoload.php';

$mySQL  = new \Deimos\QueryBuilder\Adapter\MySQL();
$qb     = new Deimos\QueryBuilder\QueryBuilder($mySQL);

$query = $qb->update()->from('from')
    ->set('hello', 'world')
    ->set('id', 5);

var_dump((string)$query); // build Query

var_dump($query);
var_dump($query->attributes());
