<?php

namespace Deimos\QueryBuilder;

interface Adapter
{

    /**
     * @return array
     */
    public function columns();

    /**
     * @return int
     */
    public function insertId();

    /**
     * @param string $string
     *
     * @return string
     */
    public function quote($string);

    /**
     * @return \PDO
     */
    public function database();

    /**
     * @return string
     */
    public function name();

    /**
     * @return string
     */
    public function port();

    /**
     * @return string
     */
    public function host();

    /**
     * @return string
     */
    public function dsn();

}