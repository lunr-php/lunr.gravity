<?php

/**
 * This file contains the MySQLSimpleDMLQueryBuilderConditionalTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2013 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MySQL\Tests;

/**
 * This class contains conditional tests for the MySQLSimpleDMLQueryBuilder class.
 *
 * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder
 */
class MySQLSimpleDMLQueryBuilderConditionalTest extends MySQLSimpleDMLQueryBuilderTestCase
{

    /**
     * Test on().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::on
     */
    public function testOn(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('left')
                      ->andReturn('`left`');
        $this->escaper->expects('column')
                      ->once()
                      ->with('right')
                      ->andReturn('`right`');

        $this->builder->expects($this->once())
                      ->method('on')
                      ->with('`left`', '`right`')
                      ->willReturnSelf();

        $this->class->on('left', 'right');
    }

    /**
     * Test on_like().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::on_like
     */
    public function testOnLike(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('left')
                      ->andReturn('`left`');

        $this->builder->expects($this->once())
                      ->method('on_like')
                      ->with('`left`')
                      ->willReturnSelf();

        $this->class->on_like('left', 'right');
    }

    /**
     * Test on_in().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::on_in
     */
    public function testOnIn(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('left')
                      ->andReturn('`left`');

        $this->escaper->expects('query_value')
                      ->once()
                      ->with('SELECT column FROM table')
                      ->andReturn('(SELECT column FROM table)');

        $this->builder->expects($this->once())
                      ->method('on_in')
                      ->with('`left`', '(SELECT column FROM table)', FALSE)
                      ->willReturnSelf();

        $this->class->on_in('left', 'SELECT column FROM table');
    }

    /**
     * Test on_in_list().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::on_in_list
     */
    public function testOnInList(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('left')
                      ->andReturn('`left`');

        $this->escaper->expects('value')
                      ->once()
                      ->with('val1')
                      ->andReturn('"val1"');
        $this->escaper->expects('value')
                      ->once()
                      ->with('val2')
                      ->andReturn('"val2"');

        $this->escaper->expects('list_value')
                      ->once()
                      ->with([ '"val1"', '"val2"' ])
                      ->andReturn('("val1", "val2")');

        $this->builder->expects($this->once())
                      ->method('on_in')
                      ->with('`left`', '("val1", "val2")', FALSE)
                      ->willReturnSelf();

        $this->class->on_in_list('left', [ 'val1', 'val2' ]);
    }

    /**
     * Test on_between().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::on_between
     */
    public function testOnBetween(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('left')
                      ->andReturn('`left`');

        $this->escaper->expects('value')
                      ->once()
                      ->with('a')
                      ->andReturn('"a"');
        $this->escaper->expects('value')
                      ->once()
                      ->with('b')
                      ->andReturn('"b"');

        $this->builder->expects($this->once())
                      ->method('on_between')
                      ->with('`left`', '"a"', '"b"')
                      ->willReturnSelf();

        $this->class->on_between('left', 'a', 'b');
    }

    /**
     * Test on_regexp().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::on_regexp
     */
    public function testOnRegexp(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('left')
                      ->andReturn('`left`');

        $this->builder->expects($this->once())
                      ->method('on_regexp')
                      ->with('`left`')
                      ->willReturnSelf();

        $this->class->on_regexp('left', 'right');
    }

    /**
     * Test start_on_group().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::start_on_group
     */
    public function testStartOnGroupReturnsSelf(): void
    {
        $this->builder->expects($this->once())
                      ->method('start_on_group')
                      ->willReturnSelf();

        $this->class->start_on_group();
    }

    /**
     * Test end_on_group().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::end_on_group
     */
    public function testEndOnGroupReturnsSelf(): void
    {
        $this->builder->expects($this->once())
                      ->method('end_on_group')
                      ->willReturnSelf();

        $this->class->end_on_group();
    }

    /**
     * Test on_null().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::on_null
     */
    public function testOnNull(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('left')
                      ->andReturn('`left`');

        $this->builder->expects($this->once())
                      ->method('on_null')
                      ->with('`left`')
                      ->willReturnSelf();

        $this->class->on_null('left');
    }

    /**
     * Test negate on_null().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::on_null
     */
    public function testOnNotNull(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('left')
                      ->andReturn('`left`');

        $this->builder->expects($this->once())
                      ->method('on_null')
                      ->with('`left`')
                      ->willReturn($this->builder);

        $this->class->on_null('left', TRUE);
    }

    /**
     * Test where().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::where
     */
    public function testWhere(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('left')
                      ->andReturn('`left`');
        $this->escaper->expects('value')
                      ->once()
                      ->with('right')
                      ->andReturn('"right"');

        $this->builder->expects($this->once())
                      ->method('where')
                      ->with('`left`')
                      ->willReturnSelf();

        $this->class->where('left', 'right');
    }

    /**
     * Test start_where_group() gets called correctly.
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::start_where_group
     */
    public function testStartWhereGroup(): void
    {
        $this->builder->expects($this->once())
                      ->method('start_where_group')
                      ->willReturnSelf();

        $this->class->start_where_group();
    }

    /**
     * Test end_where_group() gets called correctly.
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::end_where_group
     */
    public function testEndWhereGroup(): void
    {
        $this->builder->expects($this->once())
                      ->method('end_where_group')
                      ->willReturnSelf();

        $this->class->end_where_group();
    }

    /**
     * Test sql_or() gets called correctly.
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::sql_or
     */
    public function testSqlOr(): void
    {
        $this->builder->expects($this->once())
                      ->method('sql_or')
                      ->willReturnSelf();

        $this->class->sql_or();
    }

    /**
     * Test or() gets called correctly.
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::or
     */
    public function testOr(): void
    {
        $this->builder->expects($this->once())
                      ->method('or')
                      ->willReturnSelf();

        $this->class->or();
    }

    /**
     * Test sql_and() gets called correctly.
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::sql_and
     */
    public function testSqlAnd(): void
    {
        $this->builder->expects($this->once())
                      ->method('sql_and')
                      ->willReturnSelf();

        $this->class->sql_and();
    }

    /**
     * Test and() gets called correctly.
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::and
     */
    public function testAnd(): void
    {
        $this->builder->expects($this->once())
                      ->method('and')
                      ->willReturnSelf();

        $this->class->and();
    }

    /**
     * Test xor() gets called correctly.
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::xor
     */
    public function testXor(): void
    {
        $this->builder->expects($this->once())
                      ->method('xor')
                      ->willReturnSelf();

        $this->class->xor();
    }

    /**
     * Test where_like().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::where_like
     */
    public function testWhereLike(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('left')
                      ->andReturn('`left`');

        $this->builder->expects($this->once())
                      ->method('where_like')
                      ->with('`left`')
                      ->willReturnSelf();

        $this->class->where_like('left', 'right');
    }

    /**
     * Test where_in().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::where_in
     */
    public function testWhereIn(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('left')
                      ->andReturn('`left`');

        $this->escaper->expects('query_value')
                      ->once()
                      ->with('SELECT column FROM table')
                      ->andReturn('(SELECT column FROM table)');

        $this->builder->expects($this->once())
                      ->method('where_in')
                      ->with('`left`', '(SELECT column FROM table)', FALSE)
                      ->willReturnSelf();

        $this->class->where_in('left', 'SELECT column FROM table');
    }

    /**
     * Test where_in_list().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::where_in_list
     */
    public function testWhereInList(): void
    {
        $this->escaper->expects('value')
                      ->once()
                      ->with('val1')
                      ->andReturn('"val1"');
        $this->escaper->expects('value')
                      ->once()
                      ->with('val2')
                      ->andReturn('"val2"');

        $this->escaper->expects('column')
                      ->once()
                      ->with('left')
                      ->andReturn('`left`');

        $this->escaper->expects('list_value')
                      ->once()
                      ->with([ '"val1"', '"val2"' ])
                      ->andReturn('("val1", "val2")');

        $this->builder->expects($this->once())
                      ->method('where_in')
                      ->with('`left`', '("val1", "val2")', FALSE)
                      ->willReturnSelf();

        $this->class->where_in_list('left', [ 'val1', 'val2' ]);
    }

    /**
     * Test where_between().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::where_between
     */
    public function testWhereBetween(): void
    {

        $this->escaper->expects('column')
                      ->once()
                      ->with('left')
                      ->andReturn('`left`');

        $this->escaper->expects('value')
                      ->once()
                      ->with('a')
                      ->andReturn('"a"');
        $this->escaper->expects('value')
                      ->once()
                      ->with('b')
                      ->andReturn('"b"');

        $this->builder->expects($this->once())
                      ->method('where_between')
                      ->with('`left`', '"a"', '"b"')
                      ->willReturnSelf();

        $this->class->where_between('left', 'a', 'b');
    }

    /**
     * Test where_regexp().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::where_regexp
     */
    public function testWhereRegexp(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('left')
                      ->andReturn('`left`');

        $this->builder->expects($this->once())
                      ->method('where_regexp')
                      ->with('`left`', 'right')
                      ->willReturnSelf();

        $this->class->where_regexp('left', 'right');
    }

    /**
     * Test where_null().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::where_null
     */
    public function testWhereNull(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('left')
                      ->andReturn('`left`');

        $this->builder->expects($this->once())
                      ->method('where_null')
                      ->with('`left`')
                      ->willReturnSelf();

        $this->class->where_null('left');
    }

    /**
     * Test negate where_null().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::where_null
     */
    public function testWhereNotNull(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('left')
                      ->andReturn('`left`');

        $this->builder->expects($this->once())
                      ->method('where_null')
                      ->with('`left`', TRUE)
                      ->willReturnSelf();

        $this->class->where_null('left', TRUE);
    }

    /**
     * Test having().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::having
     */
    public function testHaving(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('left')
                      ->andReturn('`left`');
        $this->escaper->expects('value')
                      ->once()
                      ->with('right')
                      ->andReturn('"right"');

        $this->builder->expects($this->once())
                      ->method('having')
                      ->with('`left`', '"right"')
                      ->willReturnSelf();

        $this->class->having('left', 'right');
    }

    /**
     * Test having_like().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::having_like
     */
    public function testHavingLike(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('left')
                      ->andReturn('`left`');

        $this->builder->expects($this->once())
                      ->method('having_like')
                      ->with('`left`', 'right')
                      ->willReturnSelf();

        $this->class->having_like('left', 'right');
    }

    /**
     * Test having_in().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::having_in
     */
    public function testHavingIn(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('left')
                      ->andReturn('`left`');

        $this->escaper->expects('query_value')
                      ->once()
                      ->with('SELECT column FROM table')
                      ->andReturn('(SELECT column FROM table)');

        $this->builder->expects($this->once())
                      ->method('having_in')
                      ->with('`left`', '(SELECT column FROM table)', FALSE)
                      ->willReturnSelf();

        $this->class->having_in('left', 'SELECT column FROM table');
    }

    /**
     * Test having_in_list().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::having_in_list
     */
    public function testHavingInList(): void
    {
        $this->escaper->expects('value')
                      ->once()
                      ->with('val1')
                      ->andReturn('"val1"');
        $this->escaper->expects('value')
                      ->once()
                      ->with('val2')
                      ->andReturn('"val2"');

        $this->escaper->expects('column')
                      ->once()
                      ->with('left')
                      ->andReturn('`left`');

        $this->escaper->expects('list_value')
                      ->once()
                      ->with([ '"val1"', '"val2"' ])
                      ->andReturn('("val1", "val2")');

        $this->builder->expects($this->once())
                      ->method('having_in')
                      ->with('`left`', '("val1", "val2")', FALSE)
                      ->willReturnSelf();

        $this->class->having_in_list('left', [ 'val1', 'val2' ]);
    }

    /**
     * Test having_between().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::having_between
     */
    public function testHavingBetween(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('left')
                      ->andReturn('`left`');

        $this->escaper->expects('value')
                      ->once()
                      ->with('a')
                      ->andReturn('"a"');
        $this->escaper->expects('value')
                      ->once()
                      ->with('b')
                      ->andReturn('"b"');

        $this->builder->expects($this->once())
                      ->method('having_between')
                      ->with('`left`', '"a"', '"b"')
                      ->willReturnSelf();

        $this->class->having_between('left', 'a', 'b');
    }

    /**
     * Test having_regexp().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::having_regexp
     */
    public function testHavingRegexp(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('left')
                      ->andReturn('`left`');

        $this->builder->expects($this->once())
                      ->method('having_regexp')
                      ->with('`left`', 'right')
                      ->willReturnSelf();

        $this->class->having_regexp('left', 'right');
    }

    /**
     * Test having_null().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::having_null
     */
    public function testHavingNull(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('left')
                      ->andReturn('`left`');

        $this->builder->expects($this->once())
                      ->method('having_null')
                      ->with('`left`', FALSE)
                      ->willReturnSelf();

        $this->class->having_null('left');
    }

    /**
     * Test negate having_null().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::having_null
     */
    public function testHavingNotNull(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('left')
                      ->andReturn('`left`');

        $this->builder->expects($this->once())
                      ->method('having_null')
                      ->with('`left`', TRUE)
                      ->willReturnSelf();

        $this->class->having_null('left', TRUE);
    }

        /**
     * Test start_having_group().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::start_having_group
     */
    public function testStartHavingGroup(): void
    {
        $this->builder->expects($this->once())
                      ->method('start_having_group')
                      ->willReturnSelf();

        $this->class->start_having_group();
    }

    /**
     * Test end_having_group().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::end_having_group
     */
    public function testEndHavingGroup(): void
    {
        $this->builder->expects($this->once())
                      ->method('end_having_group')
                      ->willReturnSelf();

        $this->class->end_having_group();
    }

}

?>
