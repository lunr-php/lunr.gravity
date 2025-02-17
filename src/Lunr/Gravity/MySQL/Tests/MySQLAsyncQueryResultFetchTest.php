<?php

/**
 * This file contains the MySQLAsyncQueryResultFetchTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2012 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MySQL\Tests;

/**
 * This class contains tests for the fetching of data in the MySQLAsyncQueryResult class.
 *
 * @covers Lunr\Gravity\MySQL\MySQLAsyncQueryResult
 */
class MySQLAsyncQueryResultFetchTest extends MySQLAsyncQueryResultTestCase
{

    /**
     * Test that has_failed() tries to fetch data if it isn't already fetched.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\MySQL\MySQLAsyncQueryResult::has_failed
     */
    public function testHasFailedFetchesDataIfFetchedIsFalse(): void
    {
        $this->mockFunction('mysqli_affected_rows', fn() => 0);

        $this->mysqli->expects($this->once())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->class->has_failed();

        $this->unmockFunction('mysqli_affected_rows');
    }

    /**
     * Test that has_failed() doesn't try to fetch data if it is already fetched.
     *
     * @covers Lunr\Gravity\MySQL\MySQLAsyncQueryResult::has_failed
     */
    public function testHasFailedDoesNotFetchDataIfFetchedIsTrue(): void
    {
        $property = $this->reflection->getProperty('fetched');
        $property->setAccessible(TRUE);
        $property->setValue($this->class, TRUE);

        $this->mysqli->expects($this->never())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->class->has_failed();

        $property->setValue($this->class, FALSE);
    }

    /**
     * Test that affected_rows() tries to fetch data if it isn't already fetched.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\MySQL\MySQLAsyncQueryResult::affected_rows
     */
    public function testAffectedRowsFetchesDataIfFetchedIsFalse(): void
    {
        $this->mockFunction('mysqli_affected_rows', fn() => 0);

        $this->mysqli->expects($this->once())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->class->affected_rows();

        $this->unmockFunction('mysqli_affected_rows');
    }

    /**
     * Test that affected_rows() doesn't try to fetch data if it is already fetched.
     *
     * @covers Lunr\Gravity\MySQL\MySQLAsyncQueryResult::affected_rows
     */
    public function testAffectedRowsDoesNotFetchDataIfFetchedIsTrue(): void
    {
        $property = $this->reflection->getProperty('fetched');
        $property->setAccessible(TRUE);
        $property->setValue($this->class, TRUE);

        $this->mysqli->expects($this->never())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->class->affected_rows();

        $property->setValue($this->class, FALSE);
    }

    /**
     * Test that number_of_rows() tries to fetch data if it isn't already fetched.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\MySQL\MySQLAsyncQueryResult::number_of_rows
     */
    public function testNumberOfRowsRowsFetchesDataIfFetchedIsFalse(): void
    {
        $this->mockFunction('mysqli_affected_rows', fn() => 0);

        $this->mysqli->expects($this->once())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->class->number_of_rows();

        $this->unmockFunction('mysqli_affected_rows');
    }

    /**
     * Test that number_of_rows() doesn't try to fetch data if it is already fetched.
     *
     * @covers Lunr\Gravity\MySQL\MySQLAsyncQueryResult::number_of_rows
     */
    public function testNumberOfRowsDoesNotFetchDataIfFetchedIsTrue(): void
    {
        $property = $this->reflection->getProperty('fetched');
        $property->setAccessible(TRUE);
        $property->setValue($this->class, TRUE);

        $this->mysqli->expects($this->never())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->class->number_of_rows();

        $property->setValue($this->class, FALSE);
    }

    /**
     * Test that error_message() tries to fetch data if it isn't already fetched.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\MySQL\MySQLAsyncQueryResult::error_message
     */
    public function testErrorMessageFetchesDataIfFetchedIsFalse(): void
    {
        $this->mockFunction('mysqli_affected_rows', fn() => 0);

        $this->mysqli->expects($this->once())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->class->error_message();

        $this->unmockFunction('mysqli_affected_rows');
    }

    /**
     * Test that error_message() doesn't try to fetch data if it is already fetched.
     *
     * @covers Lunr\Gravity\MySQL\MySQLAsyncQueryResult::error_message
     */
    public function testErrorMessageDoesNotFetchDataIfFetchedIsTrue(): void
    {
        $property = $this->reflection->getProperty('fetched');
        $property->setAccessible(TRUE);
        $property->setValue($this->class, TRUE);

        $this->mysqli->expects($this->never())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->class->error_message();

        $property->setValue($this->class, FALSE);
    }

    /**
     * Test that error_number() tries to fetch data if it isn't already fetched.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\MySQL\MySQLAsyncQueryResult::error_message
     */
    public function testErrorNumberFetchesDataIfFetchedIsFalse(): void
    {
        $this->mockFunction('mysqli_affected_rows', fn() => 0);

        $this->mysqli->expects($this->once())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->class->error_number();

        $this->unmockFunction('mysqli_affected_rows');
    }

    /**
     * Test that error_number() doesn't try to fetch data if it is already fetched.
     *
     * @covers Lunr\Gravity\MySQL\MySQLAsyncQueryResult::error_message
     */
    public function testErrorNumberDoesNotFetchDataIfFetchedIsTrue(): void
    {
        $property = $this->reflection->getProperty('fetched');
        $property->setAccessible(TRUE);
        $property->setValue($this->class, TRUE);

        $this->mysqli->expects($this->never())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->class->error_number();

        $property->setValue($this->class, FALSE);
    }

    /**
     * Test that insert_id() tries to fetch data if it isn't already fetched.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\MySQL\MySQLAsyncQueryResult::error_message
     */
    public function testInsertIDFetchesDataIfFetchedIsFalse(): void
    {
        $this->mockFunction('mysqli_affected_rows', fn() => 0);

        $this->mysqli->expects($this->once())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->class->insert_id();

        $this->unmockFunction('mysqli_affected_rows');
    }

    /**
     * Test that insert_id() doesn't try to fetch data if it is already fetched.
     *
     * @covers Lunr\Gravity\MySQL\MySQLAsyncQueryResult::error_message
     */
    public function testInsertIDDoesNotFetchDataIfFetchedIsTrue(): void
    {
        $property = $this->reflection->getProperty('fetched');
        $property->setAccessible(TRUE);
        $property->setValue($this->class, TRUE);

        $this->mysqli->expects($this->never())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->class->insert_id();

        $property->setValue($this->class, FALSE);
    }

    /**
     * Test that result_array() tries to fetch data if it isn't already fetched.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\MySQL\MySQLAsyncQueryResult::result_array
     */
    public function testResultArrayFetchesDataIfFetchedIsFalse(): void
    {
        $this->mockFunction('mysqli_affected_rows', fn() => 0);

        $this->mysqli->expects($this->once())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->class->result_array();

        $this->unmockFunction('mysqli_affected_rows');
    }

    /**
     * Test that result_array() doesn't try to fetch data if it is already fetched.
     *
     * @covers Lunr\Gravity\MySQL\MySQLAsyncQueryResult::result_array
     */
    public function testResultArrayDoesNotFetchDataIfFetchedIsTrue(): void
    {
        $property = $this->reflection->getProperty('fetched');
        $property->setAccessible(TRUE);
        $property->setValue($this->class, TRUE);

        $this->mysqli->expects($this->never())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->class->result_array();

        $property->setValue($this->class, FALSE);
    }

    /**
     * Test that result_row() tries to fetch data if it isn't already fetched.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\MySQL\MySQLAsyncQueryResult::result_row
     */
    public function testResultRowFetchesDataIfFetchedIsFalse(): void
    {
        $this->mockFunction('mysqli_affected_rows', fn() => 0);

        $this->mysqli->expects($this->once())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->class->result_row();

        $this->unmockFunction('mysqli_affected_rows');
    }

    /**
     * Test that result_row() doesn't try to fetch data if it is already fetched.
     *
     * @covers Lunr\Gravity\MySQL\MySQLAsyncQueryResult::result_row
     */
    public function testResultRowDoesNotFetchDataIfFetchedIsTrue(): void
    {
        $property = $this->reflection->getProperty('fetched');
        $property->setAccessible(TRUE);
        $property->setValue($this->class, TRUE);

        $this->mysqli->expects($this->never())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->class->result_row();

        $property->setValue($this->class, FALSE);
    }

    /**
     * Test that result_column() tries to fetch data if it isn't already fetched.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\MySQL\MySQLAsyncQueryResult::result_column
     */
    public function testResultColumnFetchesDataIfFetchedIsFalse(): void
    {
        $this->mockFunction('mysqli_affected_rows', fn() => 0);

        $this->mysqli->expects($this->once())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->class->result_column('col');

        $this->unmockFunction('mysqli_affected_rows');
    }

    /**
     * Test that result_column() doesn't try to fetch data if it is already fetched.
     *
     * @covers Lunr\Gravity\MySQL\MySQLAsyncQueryResult::result_column
     */
    public function testResultColumnDoesNotFetchDataIfFetchedIsTrue(): void
    {
        $property = $this->reflection->getProperty('fetched');
        $property->setAccessible(TRUE);
        $property->setValue($this->class, TRUE);

        $this->mysqli->expects($this->never())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->class->result_column('col');

        $property->setValue($this->class, FALSE);
    }

    /**
     * Test that result_cell() tries to fetch data if it isn't already fetched.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\MySQL\MySQLAsyncQueryResult::result_cell
     */
    public function testResultCellFetchesDataIfFetchedIsFalse(): void
    {
        $this->mockFunction('mysqli_affected_rows', fn() => 0);

        $this->mysqli->expects($this->once())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->class->result_cell('cell');

        $this->unmockFunction('mysqli_affected_rows');
    }

    /**
     * Test that result_cell() doesn't try to fetch data if it is already fetched.
     *
     * @covers Lunr\Gravity\MySQL\MySQLAsyncQueryResult::result_cell
     */
    public function testResultCellDoesNotFetchDataIfFetchedIsTrue(): void
    {
        $property = $this->reflection->getProperty('fetched');
        $property->setAccessible(TRUE);
        $property->setValue($this->class, TRUE);

        $this->mysqli->expects($this->never())
                     ->method('reap_async_query')
                     ->will($this->returnValue(FALSE));

        $this->class->result_cell('cell');

        $property->setValue($this->class, FALSE);
    }

}

?>
