<?php

/**
 * This file contains the SQLite3DMLQueryBuilderQueryTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2013 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\SQLite3\Tests;

/**
 * This class contains the tests for getting insert/replace queries.
 *
 * @covers Lunr\Gravity\SQLite3\SQLite3DMLQueryBuilder
 */
class SQLite3DMLQueryBuilderQueryTest extends SQLite3DMLQueryBuilderTestCase
{

    /**
     * Test get insert query with undefined INTO clause.
     *
     * @covers Lunr\Gravity\SQLite3\SQLite3DMLQueryBuilder::get_insert_query
     */
    public function testGetInsertQueryWithUndefinedInto(): void
    {
        $this->setReflectionPropertyValue('columnNames', '(column1, column2)');
        $this->setReflectionPropertyValue('values', 'VALUES (1,2), (3,4)');

        $string = '';
        $this->assertEquals($string, $this->class->get_insert_query());
    }

    /**
     * Test get insert query using column names and values.
     *
     * @covers Lunr\Gravity\SQLite3\SQLite3DMLQueryBuilder::get_insert_query
     */
    public function testGetInsertValuesQuery(): void
    {
        $this->setReflectionPropertyValue('into', 'INTO table');
        $this->setReflectionPropertyValue('columnNames', '(column1, column2)');
        $this->setReflectionPropertyValue('values', 'VALUES (1,2), (3,4)');

        $string = 'INSERT INTO table (column1, column2) VALUES (1,2), (3,4)';
        $this->assertEquals($string, $this->class->get_insert_query());
    }

    /**
     * Test get insert query using SELECT.
     *
     * @covers Lunr\Gravity\SQLite3\SQLite3DMLQueryBuilder::get_insert_query
     */
    public function testGetInsertSelectQuery(): void
    {
        $this->setReflectionPropertyValue('into', 'INTO table');
        $this->setReflectionPropertyValue('selectStatement', 'SELECT column1, column2 FROM table');

        $string = 'INSERT INTO table SELECT column1, column2 FROM table';
        $this->assertEquals($string, $this->class->get_insert_query());
    }

    /**
     * Test get insert query using SELECT with ColumnNames.
     *
     * @covers Lunr\Gravity\SQLite3\SQLite3DMLQueryBuilder::get_insert_query
     */
    public function testGetInsertSelectColumnsQuery(): void
    {
        $this->setReflectionPropertyValue('into', 'INTO table');
        $this->setReflectionPropertyValue('columnNames', '(column1, column2)');
        $this->setReflectionPropertyValue('selectStatement', 'SELECT column1, column2 FROM table');

        $string = 'INSERT INTO table (column1, column2) SELECT column1, column2 FROM table';
        $this->assertEquals($string, $this->class->get_insert_query());
    }

    /**
     * Test get replace query with undefined INTO clause.
     *
     * @covers Lunr\Gravity\SQLite3\SQLite3DMLQueryBuilder::get_replace_query
     */
    public function testGetReplaceQueryWithUndefinedInto(): void
    {
        $this->setReflectionPropertyValue('columnNames', '(column1, column2)');
        $this->setReflectionPropertyValue('values', 'VALUES (1,2), (3,4)');

        $string = '';
        $this->assertEquals($string, $this->class->get_replace_query());
    }

    /**
     * Test get replace query using column names and values.
     *
     * @covers Lunr\Gravity\SQLite3\SQLite3DMLQueryBuilder::get_replace_query
     */
    public function testGetReplaceValuesQuery(): void
    {
        $this->setReflectionPropertyValue('into', 'INTO table');
        $this->setReflectionPropertyValue('columnNames', '(column1, column2)');
        $this->setReflectionPropertyValue('values', 'VALUES (1,2), (3,4)');

        $string = 'REPLACE INTO table (column1, column2) VALUES (1,2), (3,4)';
        $this->assertEquals($string, $this->class->get_replace_query());
    }

    /**
     * Test get replace query using SELECT.
     *
     * @covers Lunr\Gravity\SQLite3\SQLite3DMLQueryBuilder::get_replace_query
     */
    public function testGetReplaceSelectQuery(): void
    {
        $this->setReflectionPropertyValue('into', 'INTO table');
        $this->setReflectionPropertyValue('selectStatement', 'SELECT column1, column2 FROM table');

        $string = 'REPLACE INTO table SELECT column1, column2 FROM table';
        $this->assertEquals($string, $this->class->get_replace_query());
    }

    /**
     * Test get replace query using SELECT with ColumnNames.
     *
     * @covers Lunr\Gravity\SQLite3\SQLite3DMLQueryBuilder::get_replace_query
     */
    public function testGetReplaceSelectColumnsQuery(): void
    {
        $this->setReflectionPropertyValue('into', 'INTO table');
        $this->setReflectionPropertyValue('columnNames', '(column1, column2)');
        $this->setReflectionPropertyValue('selectStatement', 'SELECT column1, column2 FROM table');

        $string = 'REPLACE INTO table (column1, column2) SELECT column1, column2 FROM table';
        $this->assertEquals($string, $this->class->get_replace_query());
    }

    /**
     * Test query with returning statement.
     *
     * @param string $value    Returning value
     * @param string $expected Expected built query part
     *
     * @dataProvider expectedReturningDataProvider
     * @covers       Lunr\Gravity\SQLite3\SQLite3DMLQueryBuilder::returning
     */
    public function testQueryWithReturning(string $value, string $expected): void
    {
        $property = $this->reflection->getProperty('returning');

        $this->class->returning($value);

        $this->assertStringMatchesFormat($expected, $property->getValue($this->class));
    }

    /**
     * Unit Test Data Provider for returning statements.
     *
     * @return array $expectedReturn
     */
    public function expectedReturningDataProvider(): array
    {
        $expected_return   = [];
        $expected_return[] = [ '*', 'RETURNING *' ];
        $expected_return[] = [ 'id, name', 'RETURNING id, name' ];
        $expected_return[] = [ "'test'", "RETURNING 'test'" ];

        return $expected_return;
    }

}

?>
