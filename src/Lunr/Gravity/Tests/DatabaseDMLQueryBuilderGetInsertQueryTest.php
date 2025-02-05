<?php

/**
 * This file contains the DatabaseDMLQueryBuilderGetInsertQueryTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2012 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\Tests;

/**
 * This class contains the tests for the setup and the final query creation.
 *
 * @covers Lunr\Gravity\DatabaseDMLQueryBuilder
 */
class DatabaseDMLQueryBuilderGetInsertQueryTest extends DatabaseDMLQueryBuilderTestCase
{

    /**
     * Test get insert query with undefined INTO clause.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::get_insert_query
     */
    public function testGetInsertQueryWithUndefinedInto(): void
    {
        $this->expectException('\Lunr\Gravity\Exceptions\MissingTableReferenceException');
        $this->expectExceptionMessage('No into() in insert query!');

        $this->setReflectionPropertyValue('column_names', '(column1, column2)');
        $this->setReflectionPropertyValue('values', 'VALUES (1,2), (3,4)');

        $this->class->get_insert_query();
    }

    /**
     * Test get insert query using column names and values.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderImplodeQueryTest::testImplodeQueryWithDuplicateInsertModes
     * @covers  Lunr\Gravity\DatabaseDMLQueryBuilder::get_insert_query
     */
    public function testGetInsertValuesQuery(): void
    {
        $this->setReflectionPropertyValue('into', 'INTO table');
        $this->setReflectionPropertyValue('column_names', '(column1, column2)');
        $this->setReflectionPropertyValue('values', 'VALUES (1,2), (3,4)');

        $string = 'INSERT INTO table (column1, column2) VALUES (1,2), (3,4)';

        $this->assertEquals($string, $this->class->get_insert_query());
    }

    /**
     * Test get insert...returning query using column names and values.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderImplodeQueryTest::testImplodeQueryWithDuplicateInsertModes
     * @covers  Lunr\Gravity\DatabaseDMLQueryBuilder::get_insert_query
     */
    public function testGetInsertReturningValuesQuery(): void
    {
        $this->setReflectionPropertyValue('into', 'INTO table');
        $this->setReflectionPropertyValue('column_names', '(column1, column2)');
        $this->setReflectionPropertyValue('values', 'VALUES (1,2), (3,4)');
        $this->setReflectionPropertyValue('returning', 'RETURNING column1, column2');

        $string = 'INSERT INTO table (column1, column2) VALUES (1,2), (3,4) RETURNING column1, column2';

        $this->assertEquals($string, $this->class->get_insert_query());
    }

    /**
     * Test get insert query using SET.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderImplodeQueryTest::testImplodeQueryWithDuplicateInsertModes
     * @covers  Lunr\Gravity\DatabaseDMLQueryBuilder::get_insert_query
     */
    public function testGetInsertSetQuery(): void
    {
        $this->setReflectionPropertyValue('into', 'INTO table');
        $this->setReflectionPropertyValue('set', 'SET column1 = 1');

        $string = 'INSERT INTO table SET column1 = 1';

        $this->assertEquals($string, $this->class->get_insert_query());
    }

    /**
     * Test get insert query using SELECT.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderImplodeQueryTest::testImplodeQueryWithDuplicateInsertModes
     * @covers  Lunr\Gravity\DatabaseDMLQueryBuilder::get_insert_query
     */
    public function testGetInsertSelectQuery(): void
    {
        $this->setReflectionPropertyValue('into', 'INTO table');
        $this->setReflectionPropertyValue('select_statement', 'SELECT column1, column2 FROM table');

        $string = 'INSERT INTO table SELECT column1, column2 FROM table';

        $this->assertEquals($string, $this->class->get_insert_query());
    }

    /**
     * Test get insert query using SELECT with ColumnNames.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderImplodeQueryTest::testImplodeQueryWithDuplicateInsertModes
     * @covers  Lunr\Gravity\DatabaseDMLQueryBuilder::get_insert_query
     */
    public function testGetInsertSelectColumnsQuery(): void
    {
        $this->setReflectionPropertyValue('into', 'INTO table');
        $this->setReflectionPropertyValue('column_names', '(column1, column2)');
        $this->setReflectionPropertyValue('select_statement', 'SELECT column1, column2 FROM table');

        $string = 'INSERT INTO table (column1, column2) SELECT column1, column2 FROM table';

        $this->assertEquals($string, $this->class->get_insert_query());
    }

    /**
     * Test get insert query using SELECT with an invalid insert mode.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderImplodeQueryTest::testImplodeQueryWithDuplicateInsertModes
     * @covers  Lunr\Gravity\DatabaseDMLQueryBuilder::get_insert_query
     */
    public function testGetInsertSelectInvalidInsertModeQuery(): void
    {
        $this->setReflectionPropertyValue('into', 'INTO table');
        $this->setReflectionPropertyValue('column_names', '(column1, column2)');
        $this->setReflectionPropertyValue('select_statement', 'SELECT column1, column2 FROM table');
        $this->setReflectionPropertyValue('insert_mode', [ 'DELAYED', 'IGNORE' ]);

        $string = 'INSERT IGNORE INTO table (column1, column2) SELECT column1, column2 FROM table';

        $this->assertEquals($string, $this->class->get_insert_query());
    }

    /**
     * Test get insert query using column names, values and upsert.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderImplodeQueryTest::testImplodeQueryWithDuplicateInsertModes
     * @covers  Lunr\Gravity\DatabaseDMLQueryBuilder::get_insert_query
     */
    public function testGetInsertValuesUpsertQuery()
    {
        $this->setReflectionPropertyValue('into', 'INTO table');
        $this->setReflectionPropertyValue('column_names', '(column1, column2)');
        $this->setReflectionPropertyValue('values', 'VALUES (1,2), (3,4)');
        $this->setReflectionPropertyValue('upsert', 'ON CONFLICT DO NOTHING');

        $string = 'INSERT INTO table (column1, column2) VALUES (1,2), (3,4) ON CONFLICT DO NOTHING';

        $this->assertEquals($string, $this->class->get_insert_query());
    }

    /**
     * Test get insert query using SELECT with ColumnNames and upsert.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderImplodeQueryTest::testImplodeQueryWithDuplicateInsertModes
     * @covers  Lunr\Gravity\DatabaseDMLQueryBuilder::get_insert_query
     */
    public function testGetInsertSelectColumnsUpsertQuery()
    {
        $this->setReflectionPropertyValue('into', 'INTO table');
        $this->setReflectionPropertyValue('column_names', '(column1, column2)');
        $this->setReflectionPropertyValue('select_statement', 'SELECT column1, column2 FROM table');
        $this->setReflectionPropertyValue('upsert', 'ON CONFLICT DO NOTHING');

        $string = 'INSERT INTO table (column1, column2) SELECT column1, column2 FROM table ON CONFLICT DO NOTHING';

        $this->assertEquals($string, $this->class->get_insert_query());
    }

}

?>
