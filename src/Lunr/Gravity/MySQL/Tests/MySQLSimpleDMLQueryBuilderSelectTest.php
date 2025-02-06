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
                      ->will($this->returnSelf());

        $this->class->select_mode('ALL');
    }

    /**
     * Test select() with one input column.
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::select
     */
    public function testSelectWithOneColumn(): void
    {
        $this->escaper->expects($this->once())
                      ->method('result_column')
                      ->with($this->equalTo('col'))
                      ->will($this->returnValue('`col`'));

        $this->builder->expects($this->once())
                      ->method('select')
                      ->with($this->equalTo('`col`'))
                      ->will($this->returnSelf());

        $this->class->select('col');
    }

    /**
     * Test select() with multiple input columns.
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::select
     */
    public function testSelectWithMultipleColumns(): void
    {
        $this->escaper->expects($this->exactly(2))
                      ->method('result_column')
                      ->withConsecutive([ 'col' ], [ ' col' ])
                      ->will($this->returnValue('`col`'));

        $this->builder->expects($this->once())
                      ->method('select')
                      ->with($this->equalTo('`col`, `col`'))
                      ->willReturnSelf();

        $this->class->select('col, col');
    }

    /**
     * Test from().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::from
     */
    public function testFrom(): void
    {
        $this->escaper->expects($this->once())
                      ->method('table')
                      ->with($this->equalTo('table'))
                      ->will($this->returnValue('`table`'));

        $this->builder->expects($this->once())
                      ->method('from')
                      ->with($this->equalTo('`table`'))
                      ->will($this->returnSelf());

        $this->class->from('table');
    }

    /**
     * Test join().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::join
     */
    public function testJoin(): void
    {
        $this->escaper->expects($this->once())
                      ->method('table')
                      ->with($this->equalTo('table'))
                      ->will($this->returnValue('`table`'));

        $this->builder->expects($this->once())
                      ->method('join')
                      ->with($this->equalTo('`table`'))
                      ->will($this->returnSelf());

        $this->class->join('table');
    }

    /**
     * Test group_by().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::group_by
     */
    public function testGroupBy(): void
    {
        $this->escaper->expects($this->once())
                      ->method('column')
                      ->with($this->equalTo('col'))
                      ->will($this->returnValue('`col`'));

        $this->builder->expects($this->once())
                      ->method('group_by')
                      ->with($this->equalTo('`col`'))
                      ->will($this->returnSelf());

        $this->class->group_by('col');
    }

    /**
     * Test order_by().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::order_by
     */
    public function testOrderBy(): void
    {
        $this->escaper->expects($this->once())
                      ->method('column')
                      ->with($this->equalTo('col'))
                      ->will($this->returnValue('`col`'));

        $this->builder->expects($this->once())
                      ->method('order_by')
                      ->with($this->equalTo('`col`'), $this->equalTo(TRUE))
                      ->will($this->returnSelf());

        $this->class->order_by('col');
    }

    /**
     * Test limit().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::limit
     */
    public function testLimit(): void
    {
        $this->escaper->expects($this->exactly(2))
                      ->method('intvalue')
                      ->withConsecutive([ 10 ], [ -1 ])
                      ->will($this->returnArgument(0));

        $this->builder->expects($this->once())
                      ->method('limit')
                      ->with($this->equalTo(10))
                      ->will($this->returnSelf());

        $this->class->limit(10);
    }

    /**
     * Test union().
     *
     * @param string $operators UNION operator
     *
     * @dataProvider unionOperatorProvider
     * @covers       Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::union
     */
    public function testUnion($operators): void
    {
        $this->escaper->expects($this->once())
                      ->method('query_value')
                      ->with('query')
                      ->willReturn('(query)');

        $this->builder->expects($this->once())
                      ->method('union')
                      ->with('(query)', $operators)
                      ->willReturnSelf();

        $this->class->union('query', $operators);
    }

}

?>
