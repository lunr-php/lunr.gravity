<?php

/**
 * This file contains the MySQLSimpleDMLQueryBuilderFluidInterfaceTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2013 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MySQL\Tests;

/**
 * This class contains select tests for the MySQLSimpleDMLQueryBuilder class.
 *
 * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder
 */
class MySQLSimpleDMLQueryBuilderFluidInterfaceTest extends MySQLSimpleDMLQueryBuilderTestCase
{

    /**
     * Test the fluid interface of into().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::into
     */
    public function testIntoReturnsSelf(): void
    {
        $this->escaper->expects('table')
                      ->once()
                      ->with('table')
                      ->andReturn('`table`');

        $this->builder->expects($this->once())
                      ->method('into')
                      ->with('`table`')
                      ->willReturnSelf();

        $return = $this->class->into('table');

        $this->assertSame($this->class, $return);
    }

    /**
     * Test the fluid interface of column_names().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::column_names
     */
    public function testColumnNamesReturnsSelf(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('col')
                      ->andReturn('`col`');

        $this->builder->expects($this->once())
                      ->method('column_names')
                      ->with([ '`col`' ])
                      ->willReturnSelf();

        $return = $this->class->column_names([ 'col' ]);

        $this->assertSame($this->class, $return);
    }

    /**
     * Test the fluid interface of select().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::select
     */
    public function testSelectReturnsSelf(): void
    {
        $this->escaper->expects('result_column')
                      ->once()
                      ->with('col')
                      ->andReturn('`col`');

        $this->builder->expects($this->once())
                      ->method('select')
                      ->with('`col`')
                      ->willReturnSelf();

        $return = $this->class->select('col');

        $this->assertSame($this->class, $return);
    }

    /**
     * Test the fluid interface of from().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::from
     */
    public function testFromReturnsSelf(): void
    {
        $this->escaper->expects('table')
                      ->once()
                      ->with('table')
                      ->andReturn('`table`');

        $this->builder->expects($this->once())
                      ->method('from')
                      ->with('`table`')
                      ->willReturnSelf();

        $return = $this->class->from('table');

        $this->assertSame($this->class, $return);
    }

    /**
     * Test the fluid interface of join().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::join
     */
    public function testJoinReturnsSelf(): void
    {
        $this->escaper->expects('table')
                      ->once()
                      ->with('table')
                      ->andReturn('`table`');

        $this->builder->expects($this->once())
                      ->method('join')
                      ->with('`table`')
                      ->willReturnSelf();

        $return = $this->class->join('table');

        $this->assertSame($this->class, $return);
    }

    /**
     * Test the fluid interface of on().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::on
     */
    public function testOnReturnsSelf(): void
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

        $return = $this->class->on('left', 'right');

        $this->assertSame($this->class, $return);
    }

    /**
     * Test the fluid interface of on_like().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::on_like
     */
    public function testOnLikeReturnsSelf(): void
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
                      ->method('on_like')
                      ->with('`left`', 'right')
                      ->willReturnSelf();

        $return = $this->class->on_like('left', 'right');

        $this->assertSame($this->class, $return);
    }

    /**
     * Test the fluid interface of on_in().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::on_in
     */
    public function testOnInReturnsSelf(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('col')
                      ->andReturn('`col`');
        $this->escaper->expects('query_value')
                      ->once()
                      ->with('SELECT column FROM table')
                      ->andReturnArg(0);

        $this->builder->expects($this->once())
                      ->method('on_in')
                      ->with('`col`', 'SELECT column FROM table', FALSE)
                      ->willReturnSelf();

        $return = $this->class->on_in('col', 'SELECT column FROM table');

        $this->assertSame($this->class, $return);
    }

    /**
     * Test the fluid interface of on_in_list().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::on_in_list
     */
    public function testOnInListReturnsSelf(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('col')
                      ->andReturn('`col`');
        $this->escaper->expects('value')
                      ->once()
                      ->with( 'val')
                      ->andReturn('"val"');
        $this->escaper->expects('list_value')
                      ->once()
                      ->with( [ '"val"' ])
                      ->andReturn('("val")');

        $this->builder->expects($this->once())
                      ->method('on_in')
                      ->with('`col`', '("val")', FALSE)
                      ->willReturnSelf();

        $return = $this->class->on_in_list('col', [ 'val' ]);

        $this->assertSame($this->class, $return);
    }

    /**
     * Test the fluid interface of on_between().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::on_between
     */
    public function testOnBetweenReturnsSelf(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('col')
                      ->andReturn('`col`');
        $this->escaper->expects('value')
                      ->once()
                      ->with('lower')
                      ->andReturnArg(0);
        $this->escaper->expects('value')
                      ->once()
                      ->with('upper')
                      ->andReturnArg(0);

        $this->builder->expects($this->once())
                      ->method('on_between')
                      ->with('`col`', 'lower', 'upper', FALSE)
                      ->willReturnSelf();

        $return = $this->class->on_between('col', 'lower', 'upper');

        $this->assertSame($this->class, $return);
    }

    /**
     * Test the fluid interface of on_regexp().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::on_regexp
     */
    public function testOnRegexpReturnsSelf(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('col')
                      ->andReturn('`col`');

        $this->builder->expects($this->once())
                      ->method('on_regexp')
                      ->with('`col`', 'val', FALSE)
                      ->willReturnSelf();

        $return = $this->class->on_regexp('col', 'val');

        $this->assertSame($this->class, $return);
    }

    /**
     * Test the fluid interface of start_on_group().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::start_on_group
     */
    public function testStartOnGroupReturnsSelf(): void
    {
        $this->builder->expects($this->once())
                      ->method('start_on_group')
                      ->willReturnSelf();

        $return = $this->class->start_on_group();

        $this->assertSame($this->class, $return);
    }

    /**
     * Test the fluid interface of end_on_group().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::end_on_group
     */
    public function testEndOnGroupReturnsSelf(): void
    {
        $this->builder->expects($this->once())
                      ->method('end_on_group')
                      ->willReturnSelf();

        $return = $this->class->end_on_group();

        $this->assertSame($this->class, $return);
    }

    /**
     * Test the fluid interface of start_having_group().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::start_having_group
     */
    public function testStartHavingGroupReturnsSelf(): void
    {
        $this->builder->expects($this->once())
                      ->method('start_having_group')
                      ->willReturnSelf();

        $return = $this->class->start_having_group();

        $this->assertSame($this->class, $return);
    }

    /**
     * Test the fluid interface of end_having_group().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::end_having_group
     */
    public function testEndHavingGroupReturnsSelf(): void
    {
        $this->builder->expects($this->once())
                      ->method('end_having_group')
                      ->willReturnSelf();

        $return = $this->class->end_having_group();

        $this->assertSame($this->class, $return);
    }

    /**
     * Test the fluid interface of where().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::where
     */
    public function testWhereReturnsSelf(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('col')
                      ->andReturn('`col`');
        $this->escaper->expects('value')
                      ->once()
                      ->with('val')
                      ->andReturn('"val"');

        $this->builder->expects($this->once())
                      ->method('where')
                      ->with('`col`', '"val"', '=')
                      ->willReturnSelf();

        $return = $this->class->where('col', 'val');

        $this->assertSame($this->class, $return);
    }

    /**
     * Test the fluid interface of where_like().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::where_like
     */
    public function testWhereLikeReturnsSelf(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('col')
                      ->andReturn('`col`');

        $this->builder->expects($this->once())
                      ->method('where_like')
                      ->with('`col`', 'val', FALSE)
                      ->willReturnSelf();

        $return = $this->class->where_like('col', 'val');

        $this->assertSame($this->class, $return);
    }

    /**
     * Test the fluid interface of where_in().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::where_in
     */
    public function testWhereInReturnsSelf(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('col')
                      ->andReturn('`col`');
        $this->escaper->expects('query_value')
                      ->once()
                      ->with('SELECT column FROM table')
                      ->andReturnArg(0);

        $this->builder->expects($this->once())
                      ->method('where_in')
                      ->with('`col`', 'SELECT column FROM table', FALSE)
                      ->willReturnSelf();

        $return = $this->class->where_in('col', 'SELECT column FROM table');

        $this->assertSame($this->class, $return);
    }

    /**
     * Test the fluid interface of where_in_list().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::where_in_list
     */
    public function testWhereInListReturnsSelf(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('col')
                      ->andReturn('`col`');
        $this->escaper->expects('value')
                      ->once()
                      ->with('val')
                      ->andReturn('"val"');
        $this->escaper->expects('list_value')
                      ->once()
                      ->with([ '"val"' ])
                      ->andReturn('("val")');

        $this->builder->expects($this->once())
                      ->method('where_in')
                      ->with('`col`', '("val")', FALSE)
                      ->willReturnSelf();

        $return = $this->class->where_in_list('col', [ 'val' ]);

        $this->assertSame($this->class, $return);
    }

    /**
     * Test the fluid interface of where_between().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::where_between
     */
    public function testWhereBetweenReturnsSelf(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('col')
                      ->andReturn('`col`');
        $this->escaper->expects('value')
                      ->once()
                      ->with('lower')
                      ->andReturnArg(0);
        $this->escaper->expects('value')
                      ->once()
                      ->with('upper')
                      ->andReturnArg(0);

        $this->builder->expects($this->once())
                      ->method('where_between')
                      ->with('`col`', 'lower', 'upper', FALSE)
                      ->willReturnSelf();

        $return = $this->class->where_between('col', 'lower', 'upper');

        $this->assertSame($this->class, $return);
    }

    /**
     * Test the fluid interface of where_regexp().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::where_regexp
     */
    public function testWhereRegexpReturnsSelf(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('col')
                      ->andReturn('`col`');

        $this->builder->expects($this->once())
                      ->method('where_regexp')
                      ->with('`col`', 'val', FALSE)
                      ->willReturnSelf();

        $return = $this->class->where_regexp('col', 'val');

        $this->assertSame($this->class, $return);
    }

    /**
     * Test the fluid interface of group_by().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::group_by
     */
    public function testGroupByReturnsSelf(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('col')
                      ->andReturn('`col`');

        $this->builder->expects($this->once())
                      ->method('group_by')
                      ->with('`col`', NULL)
                      ->willReturnSelf();

        $return = $this->class->group_by('col');

        $this->assertSame($this->class, $return);
    }

    /**
     * Test the fluid interface of having().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::having
     */
    public function testHavingReturnsSelf(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('col')
                      ->andReturn('`col`');
        $this->escaper->expects('value')
                      ->once()
                      ->with('val')
                      ->andReturn('"val"');

        $this->builder->expects($this->once())
                      ->method('having')
                      ->with('`col`', '"val"', '=')
                      ->willReturnSelf();

        $return = $this->class->having('col', 'val');

        $this->assertSame($this->class, $return);
    }

    /**
     * Test the fluid interface of having_like().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::having_like
     */
    public function testHavingLikeReturnsSelf(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('col')
                      ->andReturn('`col`');

        $this->builder->expects($this->once())
                      ->method('having_like')
                      ->with('`col`', 'val', FALSE)
                      ->willReturnSelf();

        $return = $this->class->having_like('col', 'val');

        $this->assertSame($this->class, $return);
    }

    /**
     * Test the fluid interface of having_in().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::having_in
     */
    public function testHavingInReturnsSelf(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('col')
                      ->andReturn('`col`');
        $this->escaper->expects('query_value')
                      ->once()
                      ->with('SELECT column FROM table')
                      ->andReturnArg(0);

        $this->builder->expects($this->once())
                      ->method('having_in')
                      ->with('`col`', 'SELECT column FROM table', FALSE)
                      ->willReturnSelf();

        $return = $this->class->having_in('col', 'SELECT column FROM table');

        $this->assertSame($this->class, $return);
    }

    /**
     * Test the fluid interface of having_in_list().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::having_in_list
     */
    public function testHavingInListReturnsSelf(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('col')
                      ->andReturn('`col`');
        $this->escaper->expects('value')
                      ->once()
                      ->with('val')
                      ->andReturn('"val"');
        $this->escaper->expects('list_value')
                      ->once()
                      ->with([ '"val"' ])
                      ->andReturn('("val")');

        $this->builder->expects($this->once())
                      ->method('having_in')
                      ->with('`col`', '("val")', FALSE)
                      ->willReturnSelf();

        $return = $this->class->having_in_list('col', [ 'val' ]);

        $this->assertSame($this->class, $return);
    }

    /**
     * Test the fluid interface of having_between().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::having_between
     */
    public function testHavingBetweenReturnsSelf(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('col')
                      ->andReturn('`col`');
        $this->escaper->expects('value')
                      ->once()
                      ->with('lower')
                      ->andReturnArg(0);
        $this->escaper->expects('value')
                      ->once()
                      ->with('upper')
                      ->andReturnArg(0);

        $this->builder->expects($this->once())
                      ->method('having_between')
                      ->with('`col`', 'lower', 'upper', FALSE)
                      ->willReturnSelf();

        $return = $this->class->having_between('col', 'lower', 'upper');

        $this->assertSame($this->class, $return);
    }

    /**
     * Test the fluid interface of having_regexp().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::having_regexp
     */
    public function testHavingRegexpReturnsSelf(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('col')
                      ->andReturn('`col`');

        $this->builder->expects($this->once())
                      ->method('having_regexp')
                      ->with('`col`', 'val', FALSE)
                      ->willReturnSelf();

        $return = $this->class->having_regexp('col', 'val');

        $this->assertSame($this->class, $return);
    }

    /**
     * Test the fluid interface of order_by().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::order_by
     */
    public function testOrderByReturnsSelf(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('col')
                      ->andReturn('`col`');

        $this->builder->expects($this->once())
                      ->method('order_by')
                      ->with('`col`', TRUE)
                      ->willReturnSelf();

        $return = $this->class->order_by('col');

        $this->assertSame($this->class, $return);
    }

    /**
     * Test the fluid interface of limit().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::limit
     */
    public function testLimitReturnsSelf(): void
    {
        $this->escaper->expects('intvalue')
                      ->once()
                      ->with(10)
                      ->andReturnArg(0);
        $this->escaper->expects('intvalue')
                      ->once()
                      ->with(-1)
                      ->andReturnArg(0);

        $this->builder->expects($this->once())
                      ->method('limit')
                      ->with(10, -1)
                      ->willReturnSelf();

        $return = $this->class->limit(10);

        $this->assertSame($this->class, $return);
    }

    /**
     * Test the fluid interface of on_duplicate_key_update().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::on_duplicate_key_update
     */
    public function testOnDuplicateKeyUpdate()
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('col')
                      ->andReturn('`col`');

        $this->builder->expects($this->once())
                      ->method('on_duplicate_key_update')
                      ->with('col=col+1')
                      ->willReturnSelf();

        $return = $this->class->on_duplicate_key_update('col=col+1');

        $this->assertSame($this->class, $return);
    }

}

?>
