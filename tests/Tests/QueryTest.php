<?php

namespace Tests;

use Deimos\QueryBuilder\Adapter;
use Deimos\QueryBuilder\QueryBuilder;
use Deimos\QueryBuilder\RawQuery;

class QueryTest extends \PHPUnit_Framework_TestCase
{

    private $regexp = [
        '(DELETE|INSERT|UPDATE)\s+(FROM|INTO)?\s*(\w+)\s*(?:WHERE)?',
        '(\w+)\s*(=|>|<|>=|<=|<>|LIKE|IS|IS NOT|NOT LIKE)\s+(\?)',
        '\s+(AND|XOR|OR)\s+'
    ];

    private $table = 'from';

    private $call = 0;

    /**
     * @var string (update,create,insert, etc)
     */
    private $instruction;

    private $operatorWhere = 'where';

    /**
     * @param string $method
     * @param \Deimos\QueryBuilder\AbstractAdapter $adapter
     *
     * @return \Deimos\QueryBuilder\Instruction\Select
     */
    protected function prepare($method, $adapter = Adapter\MySQL::class)
    {
        $qb = new QueryBuilder(new $adapter());

        /**
         * @var \Deimos\QueryBuilder\Instruction\Select $query
         */
        $query = $qb->$method();

        if($this->instruction !== 'insert')
        {

            $query
                ->{$this->operatorWhere}('id', 5)
                ->{$this->operatorWhere . 'Xor'}('id', 5)
                ->{$this->operatorWhere}([
                    'OR' => [
                        ['id', 9],
                        ['id', 6],
                    ]
                ])
                ->{$this->operatorWhere . 'Or'}('name', 'LIKE', '%123x%')
                ->{$this->operatorWhere}('id', 5)
                ->limit(__LINE__);
        }

        if($this->instruction === 'select')
        {
            $query
                ->offset($this->call++ ? __LINE__ : null)
                ->orderBy('id', 'DESC')
                ->groupBy('id');
        }

        return $query
            ->from($this->table);
    }

    private function splitQuotes($str, $quotes = '\'"`')
    {
        return preg_replace('/[' . $quotes . ']/', '', $str);
    }

    protected function methodTest($method, $from = 'FROM')
    {
        $instruction = $this->prepare($method);

        $str = $this->splitQuotes((string)$instruction);

        preg_match('~' . $this->regexp[0] . '~', $str, $testArray);

        unset($testArray[0]);
        $this->assertEquals(
            $testArray,
            [strtoupper($this->instruction), $from, $this->table],
            '', 0.0, 10, true
        );

        if($this->instruction != 'insert')
        {
            preg_match_all('~' . $this->regexp[1] . '~', $str, $testArray);
            unset($testArray[0]);
            $this->assertEquals(
                $testArray,
                [
                    ['id', 'id', 'id', 'id', 'name', 'id'],
                    ['=', '=', '=', '=', 'LIKE', '='],
                    ['?', '?', '?', '?', '?', '?'],
                ],
                '', 0.0, 10, true
            );

            preg_match_all('~' . $this->regexp[2] . '~', $str, $testArray);
            unset($testArray[0]);
            $this->assertEquals(
                $testArray[1],
                ['XOR', 'XOR', 'OR', 'OR', 'AND'],
                '', 0.0, 10, true
            );
        }
    }

    public function testDelete()
    {
        $this->instruction = 'delete';
        $this->methodTest('delete');
    }

    public function testUpdate()
    {
        $this->instruction = 'update';
        $this->methodTest('update', '');

        $Q = $this->prepare('update');

        $Q->set('field', 'value');

        var_dump((string)$Q);
    }

    public function testInsert()
    {
        $this->instruction = 'insert';
        $this->methodTest('create', 'INTO');
    }

    public function testSelect()
    {
        $this->instruction = 'select';
        $Q = $this->prepare('query');

        $this->assertRegExp(
            '~SELECT\s+name\s+AS\s+n.\sid.*\sFROM~',
            $this->splitQuotes((string)$Q->setSelect(['n' => 'name'])->select('id'))
        );
    }

    /**
     */
    public function testSelectEmpty()
    {
        $this->instruction = 'select';

        $Q = $this->prepare('query');

        $this->assertRegExp(
            '~SELECT\s+\*\sFROM~',
            $this->splitQuotes((string)$Q->setSelect([]))
        );
    }

    /**
     * select RAW query \\ live hack
     */
    public function testSelectLiveHack()
    {
        $this->instruction = 'select';

        $Q = $this->prepare('query');

        (string)$Q->select([new RawQuery('COUNT(id) as count')])->orderBy(new RawQuery('RAND()'));
    }

    /**
     * @expectedException \Deimos\QueryBuilder\Exceptions\NotFound
     */
    public function testSelectNotFound()
    {
        $this->instruction = 'select';

        $Q = $this->prepare('query');

        (string)$Q->select(new RawQuery('COUNT(id) as count'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testErrorWhere()
    {

        $qb = new QueryBuilder(new Adapter\MySQL());

        $qb->query()
            ->from(['table' => $this->table])
            ->{$this->operatorWhere}();
    }

    public function testLiveHack()
    {
        $qb = new QueryBuilder(new Adapter\MySQL());

        (string)$qb->query()
            ->from($this->table)
            ->{$this->operatorWhere}('id', __LINE__);

        (string)$qb->query()
            ->from($this->table)
            ->{$this->operatorWhere}('id', $qb->raw('sum(1)'));

        (string)$qb->query()
            ->from($this->table)
            ->{$this->operatorWhere}('id', 'IN', [
                __LINE__,
                __LINE__,
                __LINE__,
            ]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testHavingError()
    {
        $this->operatorWhere = 'having';
        $this->testErrorWhere();
    }

    public function testHaving()
    {
        $this->operatorWhere = 'having';

        $this->testLiveHack();
        $this->testSelect();
    }

    public function testJoin()
    {
        $qb = new QueryBuilder(new Adapter\MySQL());

        $str = (string)$qb->query()
            ->from(['deimos' => 'deimosTable'])
            ->orderBy('id')
            ->join(['deptOld' => 'departments'])
            ->right()
            ->on('deimos.deptOldId', 'deptOld.id');

        $this->assertRegExp(
            '~SELECT.*\s+FROM\s+(\w+)\sAS\s(?<alias>\w+)\s+(LEFT|RIGHT|INNER)?\s+JOIN\s+(\w+)\s+AS\s+(?<key>\w+)\s+ON\s+(\k<alias>).*=\s+(\k<key>).*~',
            $this->splitQuotes($str)
        );
    }

}
