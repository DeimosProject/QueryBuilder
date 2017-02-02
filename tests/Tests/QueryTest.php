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
    private $instructionMethod;

    private $operatorWhere = 'where';

    /**
     * @var \Deimos\QueryBuilder\Operator\From
     */
    private $instruction;

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

        if ($this->instructionMethod !== 'insert') {

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

        if ($this->instructionMethod === 'select') {
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
        $this->instruction = $this->prepare($method);

        $str = $this->splitQuotes((string)$this->instruction);

        preg_match('~' . $this->regexp[0] . '~', $str, $testArray);

        unset($testArray[0]);
        $this->assertEquals(
            $testArray,
            [strtoupper($this->instructionMethod), $from, $this->table],
            '', 0.0, 10, true
        );

        if ($this->instructionMethod != 'insert') {
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
        $this->instructionMethod = 'delete';
        $this->methodTest('delete');
    }

    public function testUpdate()
    {
        $this->instructionMethod = 'update';
        $this->methodTest('update', '');

        $Q = $this->prepare('update');

        $Q->set('field', 'value');
        $Q->set('name', 'Alex');

        preg_match('~UPDATE\s+\w+\s+SET\s+(\w+)\s*=\s*\?,\s*(\w+)\s*=\s*\?\s*WHERE.*~', $this->splitQuotes((string)$Q), $matches);
        unset($matches[0]);

        $this->assertEquals(
            $matches,
            [1 => 'field', 2 => 'name']
        );

    }

    public function testInsert()
    {
        $this->instructionMethod = 'insert';
        $this->methodTest('create', 'INTO');

        $this->instruction->value('firld', 'value');
        $this->instruction->value('name', 'value');

        $this->assertRegExp(
            '~INSERT\s+INTO\s+\w+\s+\((\w+)\s*,\s*(\w+)\s*\)\s*VALUES\s*\(\s*\?\s*,\s*\?\s*\)~',
            $this->splitQuotes((string)$this->instruction)
        );
    }

    public function testSelect()
    {
        $this->instructionMethod = 'select';
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
        $this->instructionMethod = 'select';

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
        $this->instructionMethod = 'select';

        $Q = $this->prepare('query');

        (string)$Q->select([new RawQuery('COUNT(id) as count')])->orderBy(new RawQuery('RAND()'));
    }

    /**
     * @expectedException \Deimos\QueryBuilder\Exceptions\NotFound
     */
    public function testSelectNotFound()
    {
        $this->instructionMethod = 'select';

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

    public function testValues()
    {
        $this->instructionMethod = 'select';

        $instruction = $this->prepare('query');

        $str = $this->splitQuotes((string)$instruction);

        $this->validateBrackets($str);
    }

    /**
     * @param $code
     * @return bool
     * @throws \Exception
     */
    private function validateBrackets($code)
    {

        $stack = array();
        $pair = array(
            '(' => ')',
            '[' => ']',
            '{' => '}',
        );

        $code = (string)$code;

        $len = strlen($code);

        $i = 0;
        while($i < $len)
        {

            $ch = $code[$i];
            switch ($ch)
            {
                case '(':
                case '[':
                case '{':
                    array_push($stack, $pair[$ch]);
                    break;

                case ')':
                case ']':
                case '}':
                    if (!$stack)
                    {
                        throw new \Exception('Закрывающая "' . $ch . '", когда нечего закрывать');
                    }

                    if ($ch != end($stack))
                    {
                        throw new \Exception('Ожидалось "' . end($stack) . '", но внезапно "' . $ch . '"');
                    }

                    array_pop($stack);
            }

            $i++;
        }

        if ($stack)
        {
            throw new \Exception('В конце остались незакрыты: "' . implode('", "', array_reverse($stack)) . '"');
        }

        return true;
    }

}
