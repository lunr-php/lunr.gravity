<?php

/**
 * This file contains the MySQLQueryResultSuccessTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2012 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MySQL\Tests;

/**
 * This class contains tests for the MySQLQueryResult class
 * based on a successful query without result.
 *
 * @covers Lunr\Gravity\MySQL\MySQLQueryResult
 */
class MySQLQueryResultSuccessTest extends MySQLQueryResultTestCase
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
     * Test that the success flag is TRUE.
     */
    public function testSuccessIsTrue(): void
    {
        $this->assertTrue($this->getReflectionPropertyValue('success'));
    }

    /**
     * Test that the result value is TRUE.
     */
    public function testResultIsTrue(): void
    {
        $this->assertTrue($this->getReflectionPropertyValue('result'));
    }

    /**
     * Test that the freed flasg is TRUE.
     */
    public function testFreedIsTrue(): void
    {
        $this->assertTrue($this->getReflectionPropertyValue('freed'));
    }

    /**
     * Test that the has_failed() method returns FALSE.
     *
     * @covers Lunr\Gravity\MySQL\MySQLQueryResult::has_failed
     */
    public function testHasFailedReturnsFalse(): void
    {
        $this->assertFalse($this->class->has_failed());
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
     * Test that result_array() returns an empty array.
     *
     * @covers Lunr\Gravity\MySQL\MySQLQueryResult::result_array
     */
    public function testResultArrayReturnsEmptyArray(): void
    {
        $value = $this->class->result_array();

        $this->assertIsArray($value);
        $this->assertEmpty($value);
    }

    /**
     * Test that result_row() returns an empty array.
     *
     * @covers Lunr\Gravity\MySQL\MySQLQueryResult::result_row
     */
    public function testResultRowReturnsEmptyArray(): void
    {
        $value = $this->class->result_row();

        $this->assertIsArray($value);
        $this->assertEmpty($value);
    }

    /**
     * Test that result_column() returns an empty array.
     *
     * @covers Lunr\Gravity\MySQL\MySQLQueryResult::result_column
     */
    public function testResultColumnReturnsEmptyArray(): void
    {
        $value = $this->class->result_column('column');

        $this->assertIsArray($value);
        $this->assertEmpty($value);
    }

    /**
     * Test that result_cell() returns NULL.
     *
     * @covers Lunr\Gravity\MySQL\MySQLQueryResult::result_cell
     */
    public function testResultCellReturnsNull(): void
    {
        $this->assertNull($this->class->result_cell('cell'));
    }

}

?>
