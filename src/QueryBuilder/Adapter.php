<?php

namespace Deimos\QueryBuilder;

interface Adapter
{

    /**
     * @return array
     */
    public function listColumns();

    /**
     * @return int
     */
    public function insertId();

    /**
     * @return string
     */
    public function dsn();

    /**
     * @param string $string
     *
     * @return string
     */
    public function quote($string);

    /**
     * @return \PDO
     */
    public function connection();

}