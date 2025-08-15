<?php

/**
 * This file contains the MySQLSimpleDMLQueryBuilderWriteTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2014 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MySQL\Tests;

/**
 * This class contains update/delete/insert tests for the MySQLSimpleDMLQueryBuilder class.
 *
 * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder
 */
class MySQLSimpleDMLQueryBuilderWriteTest extends MySQLSimpleDMLQueryBuilderTestCase
{

    /**
     * Test get_insert_query() gets called correctly.
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::get_insert_query
     */
    public function testGetInsertQuery(): void
    {
        $this->builder->expects($this->once())
                      ->method('get_insert_query')
                      ->willReturn('');

        $this->class->get_insert_query();
    }

    /**
     * Test get_replace_query() gets called correctly.
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::get_replace_query
     */
    public function testGetReplaceQuery(): void
    {
        $this->builder->expects($this->once())
                      ->method('get_replace_query')
                      ->willReturn('');

        $this->class->get_replace_query();
    }

    /**
     * Test get_delete_query() gets called correctly.
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::get_delete_query
     */
    public function testGetDeleteQuery(): void
    {
        $this->builder->expects($this->once())
                      ->method('get_delete_query')
                      ->willReturn('');

        $this->class->get_delete_query();
    }

    /**
     * Test get_update_query() gets called correctly.
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::get_update_query
     */
    public function testGetUpdateQuery(): void
    {
        $this->builder->expects($this->once())
                      ->method('get_update_query')
                      ->willReturn('');

        $this->class->get_update_query();
    }

    /**
     * Test insert_mode() gets called correctly.
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::insert_mode
     */
    public function testInsertMode(): void
    {
        $this->builder->expects($this->once())
                      ->method('insert_mode')
                      ->with('DELAYED')
                      ->willReturnSelf();

        $this->class->insert_mode('DELAYED');
    }

    /**
     * Test replace_mode() gets called correctly.
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::replace_mode
     */
    public function testReplaceMode(): void
    {
        $this->builder->expects($this->once())
                      ->method('replace_mode')
                      ->with('LOW_PRIORITY')
                      ->willReturnSelf();

        $this->class->replace_mode('LOW_PRIORITY');
    }

    /**
     * Test update_mode() gets called correctly.
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::update_mode
     */
    public function testUpdateMode()
    {
        $this->builder->expects($this->once())
                      ->method('update_mode')
                      ->with('IGNORE')
                      ->willReturnSelf();

        $this->class->update_mode('IGNORE');
    }

    /**
     * Test delete_mode() gets called correctly.
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::delete_mode
     */
    public function testDeleteMode(): void
    {
        $this->builder->expects($this->once())
                      ->method('delete_mode')
                      ->with('QUICK')
                      ->willReturnSelf();

        $this->class->delete_mode('QUICK');
    }

    /**
     * Test into().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::into
     */
    public function testInto(): void
    {
        $this->escaper->expects('table')
                      ->once()
                      ->with('table')
                      ->andReturn('`table`');

        $this->builder->expects($this->once())
                      ->method('into')
                      ->with('`table`')
                      ->willReturnSelf();

        $this->class->into('table');
    }

    /**
     * Test column_names() with a single column.
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::column_names
     */
    public function testColumnNamesWithOneColumn(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('col')
                      ->andReturn('`col`');

        $this->builder->expects($this->once())
                      ->method('column_names')
                      ->with([ '`col`' ])
                      ->willReturnSelf();

        $this->class->column_names([ 'col' ]);
    }

    /**
     * Test column_names() with multiple columns.
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::column_names
     */
    public function testColumnNamesWithMultipleColumns(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('col1')
                      ->andReturn('`col1`');

        $this->escaper->expects('column')
                      ->once()
                      ->with('col2')
                      ->andReturn('`col2`');

        $this->builder->expects($this->once())
                      ->method('column_names')
                      ->with([ '`col1`', '`col2`' ])
                      ->willReturnSelf();

        $this->class->column_names([ 'col1', 'col2' ]);
    }

    /**
     * Test update() with one table to update.
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::update
     */
    public function testUpdateWithOneTable(): void
    {
        $this->escaper->expects('table')
                      ->once()
                      ->with('table')
                      ->andReturn('`table`');

        $this->builder->expects($this->once())
                      ->method('update')
                      ->with('`table`')
                      ->willReturnSelf();

        $this->class->update('table');
    }

    /**
     * Test update() with multiple tables to update.
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::update
     */
    public function testUpdateWithMultipleTables(): void
    {
        $this->escaper->expects('table')
                      ->times(2)
                      ->with('table')
                      ->andReturn('`table`');

        $this->builder->expects($this->once())
                      ->method('update')
                      ->with('`table`, `table`')
                      ->willReturnSelf();

        $this->class->update('table,table');
    }

    /**
     * Test delete() gets called correctly.
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::delete
     */
    public function testDelete(): void
    {
        $this->escaper->expects('table')
                      ->once()
                      ->andReturnArg(0);

        $this->builder->expects($this->once())
                      ->method('delete')
                      ->with('')
                      ->willReturnSelf();

        $this->class->delete('');
    }

    /**
     * Test SELECT statements in INSERT INTO statements
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::select_statement
     */
    public function testSelectStatementInInto(): void
    {
        $query = 'SELECT * from `test`';
        $this->builder->expects($this->once())
                      ->method('select_statement')
                      ->with($query)
                      ->willReturnSelf();

        $this->class->select_statement($query);
    }

    /**
     * Test lock_mode() gets called correctly.
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::lock_mode
     */
    public function testLockMode(): void
    {
        $this->builder->expects($this->once())
                      ->method('lock_mode')
                      ->with('')
                      ->willReturnSelf();

        $this->class->lock_mode('');
    }

    /**
     * Test values() gets called correctly.
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::values
     */
    public function testValues(): void
    {
        $this->builder->expects($this->once())
                      ->method('values')
                      ->with('')
                      ->willReturnSelf();

        $this->class->values('');
    }

    /**
     * Test set() gets called correctly.
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::set
     */
    public function testSetClause(): void
    {
        $this->builder->expects($this->once())
                      ->method('set')
                      ->with('')
                      ->willReturnSelf();

        $this->class->set('');
    }

    /**
     * Test with() gets called correctly.
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::with
     */
    public function testWith(): void
    {
        $this->builder->expects($this->once())
                      ->method('with')
                      ->with('alias', 'query')
                      ->willReturnSelf();

        $this->class->with('alias', 'query');
    }

    /**
     * Test with_recursive() gets called correctly.
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::with_recursive
     */
    public function testWithRecursive(): void
    {
        $this->builder->expects($this->once())
                      ->method('with_recursive')
                      ->with('alias', 'anchor_query', 'recursive_query')
                      ->willReturnSelf();

        $this->class->with_recursive('alias', 'anchor_query', 'recursive_query');
    }

    /**
     * Test on_duplicate_key_update() gets called correctly.
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::on_duplicate_key_update
     */
    public function testOnDuplicateKeyUpdate()
    {
        $this->builder->expects($this->once())
                      ->method('on_duplicate_key_update')
                      ->with('col=col+1')
                      ->willReturnSelf();

        $this->class->on_duplicate_key_update('col=col+1');
    }

}

?>
