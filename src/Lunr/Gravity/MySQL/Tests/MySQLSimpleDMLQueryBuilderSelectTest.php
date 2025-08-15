<?php

/**
 * This file contains the MySQLSimpleDMLQueryBuilderSelectTest class.
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
class MySQLSimpleDMLQueryBuilderSelectTest extends MySQLSimpleDMLQueryBuilderTestCase
{

    /**
     * Test get_insert_query() gets called correctly.
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::get_select_query
     */
    public function testGetSelectQuery(): void
    {
        $this->builder->expects($this->once())
                      ->method('get_select_query')
                      ->willReturn('');

        $this->class->get_select_query();
    }

    /**
     * Test select_mode() gets called correctly.
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::select_mode
     */
    public function testSelectMode(): void
    {
        $this->builder->expects($this->once())
                      ->method('select_mode')
                      ->with('ALL')
                      ->willReturnSelf();

        $this->class->select_mode('ALL');
    }

    /**
     * Test select() with one input column.
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::select
     */
    public function testSelectWithOneColumn(): void
    {
        $this->escaper->expects('result_column')
                      ->once()
                      ->with('col')
                      ->andReturn('`col`');

        $this->builder->expects($this->once())
                      ->method('select')
                      ->with('`col`')
                      ->willReturnSelf();

        $this->class->select('col');
    }

    /**
     * Test select() with multiple input columns.
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::select
     */
    public function testSelectWithMultipleColumns(): void
    {
        $this->escaper->expects('result_column')
                      ->times(2)
                      ->with('col')
                      ->andReturn('`col`');

        $this->builder->expects($this->once())
                      ->method('select')
                      ->with('`col`, `col`')
                      ->willReturnSelf();

        $this->class->select('col,col');
    }

    /**
     * Test from().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::from
     */
    public function testFrom(): void
    {
        $this->escaper->expects('table')
                      ->once()
                      ->with('table')
                      ->andReturn('`table`');

        $this->builder->expects($this->once())
                      ->method('from')
                      ->with('`table`')
                      ->willReturnSelf();

        $this->class->from('table');
    }

    /**
     * Test join().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::join
     */
    public function testJoin(): void
    {
        $this->escaper->expects('table')
                      ->once()
                      ->with('table')
                      ->andReturn('`table`');

        $this->builder->expects($this->once())
                      ->method('join')
                      ->with('`table`')
                      ->willReturnSelf();

        $this->class->join('table');
    }

    /**
     * Test group_by().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::group_by
     */
    public function testGroupBy(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('col')
                      ->andReturn('`col`');

        $this->builder->expects($this->once())
                      ->method('group_by')
                      ->with('`col`')
                      ->willReturnSelf();

        $this->class->group_by('col');
    }

    /**
     * Test order_by().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::order_by
     */
    public function testOrderBy(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('col')
                      ->andReturn('`col`');

        $this->builder->expects($this->once())
                      ->method('order_by')
                      ->with('`col`', TRUE)
                      ->willReturnSelf();

        $this->class->order_by('col');
    }

    /**
     * Test limit().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::limit
     */
    public function testLimit(): void
    {
        $this->escaper->expects('intvalue')
                      ->once()
                      ->with( 10)
                      ->andReturnArg(0);

        $this->escaper->expects('intvalue')
                      ->once()
                      ->with( -1)
                      ->andReturnArg(0);

        $this->builder->expects($this->once())
                      ->method('limit')
                      ->with(10)
                      ->willReturnSelf();

        $this->class->limit(10);
    }

    /**
     * Test union().
     *
     * @param string|bool $operators UNION operator
     *
     * @dataProvider unionOperatorProvider
     * @covers       Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::union
     */
    public function testUnion(string|bool $operators): void
    {
        $this->escaper->expects('query_value')
                      ->once()
                      ->with('query')
                      ->andReturn('(query)');

        $this->builder->expects($this->once())
                      ->method('union')
                      ->with('(query)', $operators)
                      ->willReturnSelf();

        $this->class->union('query', $operators);
    }

}

?>
