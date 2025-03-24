<?php

/**
 * This file contains the MySQLQueryResultBaseTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2012 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MySQL\Tests;

/**
 * This class contains basic tests for the MySQLQueryResult class.
 *
 * @covers Lunr\Gravity\MySQL\MySQLQueryResult
 */
class MySQLQueryResultBaseTest extends MySQLQueryResultTestCase
{

    /**
     * TestCase Constructor.
     */
    public function setUp(): void
    {
        $this->mockFunction('mysqli_affected_rows', fn() => 10);

        $this->successfulSetup();

        $this->unmockFunction('mysqli_affected_rows');
    }

    /**
     * Test that error message is empty on successful query.
     */
    public function testErrorMessageIsEmpty(): void
    {
        $this->assertPropertyEquals('errorMessage', '');
    }

    /**
     * Test that error number is zero on successful query.
     */
    public function testErrorNumberIsZero(): void
    {
        $this->assertPropertyEquals('errorNumber', 0);
    }

    /**
     * Test that warning is NULL on successful query.
     */
    public function testWarningsIsNull(): void
    {
        $this->assertNull($this->getReflectionPropertyValue('warnings'));
    }

    /**
     * Test that error number is zero on successful query.
     */
    public function testInsertIDIsZero(): void
    {
        $this->assertPropertyEquals('insertID', 0);
    }

    /**
     * Test that affected rows is a number on successful query.
     */
    public function testAffectedRowsIsNumber(): void
    {
        $this->assertPropertyEquals('affectedRows', 10);
    }

    /**
     * Test that number of rows is a number on successful query.
     */
    public function testNumberOfRowsIsNumber(): void
    {
        $this->assertPropertyEquals('numRows', 10);
    }

    /**
     * Test that error message is empty on successful query.
     */
    public function testQueryIsPassedCorrectly(): void
    {
        $this->assertPropertyEquals('query', $this->query);
    }

    /**
     * Test that affected_rows() returns a number.
     *
     * @covers Lunr\Gravity\MySQL\MySQLQueryResult::affected_rows
     */
    public function testAffectedRowsReturnsNumber(): void
    {
        $this->setReflectionPropertyValue('affectedRows', 10);

        $value = $this->class->affected_rows();
        $this->assertIsInt($value);
        $this->assertEquals(10, $value);
    }

    /**
     * Test that number_of_rows() returns a number.
     *
     * @covers Lunr\Gravity\MySQL\MySQLQueryResult::number_of_rows
     */
    public function testNumberOfRowsReturnsNumber(): void
    {
        $this->setReflectionPropertyValue('numRows', 10);

        $value = $this->class->number_of_rows();
        $this->assertIsInt($value);
        $this->assertEquals(10, $value);
    }

    /**
     * Test that error_message() returns a string.
     *
     * @covers Lunr\Gravity\MySQL\MySQLQueryResult::error_message
     */
    public function testErrorMessageReturnsString(): void
    {
        $this->setReflectionPropertyValue('errorMessage', '');

        $value = $this->class->error_message();
        $this->assertIsString($value);
        $this->assertEquals('', $value);
    }

    /**
     * Test that error_number() returns a number.
     *
     * @covers Lunr\Gravity\MySQL\MySQLQueryResult::error_number
     */
    public function testErrorNumberReturnsNumber(): void
    {
        $this->setReflectionPropertyValue('errorNumber', 0);

        $value = $this->class->error_number();
        $this->assertIsInt($value);
        $this->assertEquals(0, $value);
    }

    /**
     * Test that insert_id() returns a number.
     *
     * @covers Lunr\Gravity\MySQL\MySQLQueryResult::insert_id
     */
    public function testInsertIDReturnsNumber(): void
    {
        $this->setReflectionPropertyValue('insertID', 0);

        $value = $this->class->insert_id();
        $this->assertIsInt($value);
        $this->assertEquals(0, $value);
    }

    /**
     * Test that query() returns a string.
     *
     * @covers Lunr\Gravity\MySQL\MySQLQueryResult::query
     */
    public function testQueryReturnsString(): void
    {
        $this->setReflectionPropertyValue('query', 'SELECT * FROM table1');

        $value = $this->class->query();
        $this->assertIsString($value);
        $this->assertEquals('SELECT * FROM table1', $value);
    }

    /**
     * Test that the mysqli class is passed by reference.
     */
    public function testMysqliIsPassedByReference(): void
    {
        $value = $this->getReflectionPropertyValue('mysqli');

        $this->assertInstanceOf('Lunr\Gravity\MySQL\Tests\MockMySQLiSuccessfulConnection', $value);
        $this->assertSame($this->mysqli, $value);
    }

    /**
     * Test that canonical_query() returns a string.
     *
     * @covers Lunr\Gravity\MySQL\MySQLQueryResult::canonical_query
     */
    public function testCanonicalQuery(): void
    {
        $this->setReflectionPropertyValue('query', 'SELECT * FROM table1 WHERE value="test"');

        $value = $this->class->canonical_query();
        $this->assertIsString($value);
        $this->assertEquals('SELECT * FROM table1 WHERE value="?"', $value);

        $value = $this->getReflectionPropertyValue('canonicalQuery');
        $this->assertIsString($value);
        $this->assertEquals('SELECT * FROM table1 WHERE value="?"', $value);
    }

    /**
     * Test that canonical_query() returns a cached string.
     *
     * @covers Lunr\Gravity\MySQL\MySQLQueryResult::canonical_query
     */
    public function testCachedCanonicalQuery(): void
    {
        $this->assertPropertyUnset('canonicalQuery');
        $this->setReflectionPropertyValue('canonicalQuery', 'SELECT * FROM table2 WHERE value=?');
        $value = $this->class->canonical_query();
        $this->assertIsString($value);
        $this->assertEquals('SELECT * FROM table2 WHERE value=?', $value);
    }

}

?>
