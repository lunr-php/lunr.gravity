<?php

/**
 * Contains SQLite3QueryResultFailedTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2012 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\SQLite3\Tests;

/**
 * This class contains the tests for testing a failed query.
 *
 * @covers Lunr\Gravity\SQLite3\SQLite3QueryResult
 */
class SQLite3QueryResultFailedTest extends SQLite3QueryResultTest
{

    /**
     * Override the default setUp with a setup with a failed query.
     */
    public function setUp(): void
    {
        $this->setUpWithFailedQuery();
    }

    /**
     * Test that the success flag is FALSE.
     */
    public function testSuccessIsFalse(): void
    {
        $property = $this->getReflectionProperty('success');

        $this->assertFalse($property->getValue($this->class));
    }

    /**
     * Test that the result value is FALSE.
     */
    public function testResultIsFalse(): void
    {
        $property = $this->getReflectionProperty('result');

        $this->assertFalse($property->getValue($this->class));
    }

    /**
     * Test that the freed flag is TRUE.
     */
    public function testFreedIsTrue(): void
    {
        $property = $this->getReflectionProperty('freed');

        $this->assertTrue($property->getValue($this->class));
    }

    /**
     * Test that the has_failed() method returns TRUE.
     *
     * @covers Lunr\Gravity\SQLite3\SQLite3QueryResult::has_failed
     */
    public function testHasFailedReturnsTrue(): void
    {
        $this->assertTrue($this->class->has_failed());
    }

    /**
     * Test that error message is a string on failed query.
     *
     * @covers Lunr\Gravity\SQLite3\SQLite3QueryResult::error_message
     */
    public function testErrorMessageIsString(): void
    {
        $property = $this->getReflectionProperty('error_message');

        $this->assertIsString($property->getValue($this->class));
    }

    /**
     * Test that error number is a number on a failed query.
     *
     * @covers Lunr\Gravity\SQLite3\SQLite3QueryResult::error_number
     */
    public function testErrorNumberIsNumber(): void
    {
        $property = $this->getReflectionProperty('error_number');

        $this->assertIsInt($property->getValue($this->class));
    }

    /**
     * Test that error number is zero on successful query.
     *
     * @covers Lunr\Gravity\SQLite3\SQLite3QueryResult::insert_id
     */
    public function testInsertIDIsZero(): void
    {
        $property = $this->getReflectionProperty('insert_id');

        $this->assertEquals(0, $property->getValue($this->class));
    }

    /**
     * Test that result_array() returns an empty array.
     *
     * @covers Lunr\Gravity\SQLite3\SQLite3QueryResult::result_array
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
     * @covers Lunr\Gravity\SQLite3\SQLite3QueryResult::result_row
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
     * @covers Lunr\Gravity\SQLite3\SQLite3QueryResult::result_column
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
     * @covers Lunr\Gravity\SQLite3\SQLite3QueryResult::result_cell
     */
    public function testResultCellReturnsNull(): void
    {
        $this->assertNull($this->class->result_cell('cell'));
    }

    /**
     * Test that the has_deadlock() method returns TRUE.
     *
     * @covers \Lunr\Gravity\SQLite3\SQLite3QueryResult::has_deadlock
     */
    public function testHasDeadlockReturnsTrue(): void
    {
        $this->setReflectionPropertyValue('error_number', 6);

        $this->assertTrue($this->class->has_deadlock());
    }

    /**
     * Test that the has_deadlock() method returns FALSE.
     *
     * @covers \Lunr\Gravity\SQLite3\SQLite3QueryResult::has_deadlock
     */
    public function testHasDeadlockReturnsFalse(): void
    {
        $this->setReflectionPropertyValue('error_number', 999);

        $this->assertFalse($this->class->has_deadlock());
    }

    /**
     * Test that the has_lock_timeout() method returns TRUE.
     *
     * @covers \Lunr\Gravity\SQLite3\SQLite3QueryResult::has_lock_timeout
     */
    public function testLockTimeoutReturnsTrue(): void
    {
        $this->setReflectionPropertyValue('error_number', 5);

        $this->assertTrue($this->class->has_lock_timeout());
    }

    /**
     * Test that the has_lock_timeout() method returns FALSE.
     *
     * @covers \Lunr\Gravity\SQLite3\SQLite3QueryResult::has_lock_timeout
     */
    public function testLockTimeoutReturnsFalse(): void
    {
        $this->setReflectionPropertyValue('error_number', 999);

        $this->assertFalse($this->class->has_lock_timeout());
    }

}

?>
