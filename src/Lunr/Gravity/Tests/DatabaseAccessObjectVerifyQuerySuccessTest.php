<?php

/**
 * This file contains the DatabaseAccessObjectVerifyQuerySuccessTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2019 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\Tests;

use Lunr\Gravity\Exceptions\DeadlockException;
use Lunr\Gravity\Exceptions\QueryException;
use Lunr\Gravity\MySQL\MySQLQueryResult;

/**
 * This class contains the tests for the DatabaseAccessObject class.
 *
 * @covers Lunr\Gravity\DatabaseAccessObject
 */
class DatabaseAccessObjectVerifyQuerySuccessTest extends DatabaseAccessObjectTestCase
{

    /**
     * Test that verify_query_success() does not throw an exception if the query was successful.
     *
     * @covers Lunr\Gravity\DatabaseAccessObject::verify_query_success
     */
    public function testVerifyQuerySuccessDoesNotThrowExceptionOnQuerySuccess(): void
    {
        $query = $this->getMockBuilder(MySQLQueryResult::class)
                      ->disableOriginalConstructor()
                      ->getMock();

        $query->expects($this->once())
              ->method('has_failed')
              ->willReturn(FALSE);

        $query->expects($this->never())
              ->method('has_deadlock');

        $this->class->verify_query_success($query);
    }

    /**
     * Test that verify_query_success() logs no warnings if there are no warnings.
     *
     * @covers Lunr\Gravity\DatabaseAccessObject::verify_query_success
     */
    public function testVerifyQueryNotLogsWarningsIfNoWarnings(): void
    {
        $query = $this->getMockBuilder(MySQLQueryResult::class)
                      ->disableOriginalConstructor()
                      ->getMock();

        $query->expects($this->once())
              ->method('warnings')
              ->willReturn(NULL);

        $this->logger->expects($this->never())
                     ->method('warning');

        $this->class->verify_query_success($query);
    }

    /**
     * Test that verify_query_success() logs warnings when there are any.
     *
     * @covers Lunr\Gravity\DatabaseAccessObject::verify_query_success
     */
    public function testVerifyQueryLogsWarnings(): void
    {
        $warnings = [
            [
                'message' => 'message1', 'sqlstate' => 'HY000', 'errno' => 1364
            ],
            [
                'message' => 'message2', 'sqlstate' => 'HY000', 'errno' => 1364
            ]
        ];

        $query = $this->getMockBuilder(MySQLQueryResult::class)
                      ->disableOriginalConstructor()
                      ->getMock();

        $query->expects($this->once())
              ->method('warnings')
              ->willReturn($warnings);

        $query->expects($this->once())
              ->method('query')
              ->willReturn('query');

        $context        = [ 'query' => 'query', 'warning_count' => 2 ];
        $warning_string = "\nHY000 (1364): message1\nHY000 (1364): message2";
        $this->logger->expects($this->once())
                     ->method('warning')
                     ->with('{query}; had {warning_count} warnings:' . $warning_string, $context);

        $this->class->verify_query_success($query);
    }

    /**
     * Test that verify_query_success() throws a QueryException in case of an error.
     *
     * @covers Lunr\Gravity\DatabaseAccessObject::verify_query_success
     */
    public function testVerifyQuerySuccessThrowsQueryExceptionOnError(): void
    {
        $query = $this->getMockBuilder(MySQLQueryResult::class)
                      ->disableOriginalConstructor()
                      ->getMock();

        $query->expects($this->once())
              ->method('has_failed')
              ->willReturn(TRUE);

        $query->expects($this->once())
              ->method('has_deadlock')
              ->willReturn(FALSE);

        $query->expects($this->exactly(2))
              ->method('error_message')
              ->willReturn('message');

        $query->expects($this->exactly(1))
              ->method('error_number')
              ->willReturn(1);

        $query->expects($this->exactly(2))
              ->method('query')
              ->willReturn('query');

        $this->logger->expects($this->once())
             ->method('error')
             ->with('{query}; failed with error: {error}', [ 'query' => 'query', 'error' => 'message' ]);

        $this->expectException(QueryException::class);
        $this->expectExceptionMessage('Database query error!');

        $this->class->verify_query_success($query);
    }

    /**
     * Test that verify_query_success() throws a DeadlockException in case of a deadlock.
     *
     * @covers Lunr\Gravity\DatabaseAccessObject::verify_query_success
     */
    public function testVerifyQuerySuccessThrowsDeadlockExceptionOnDeadlock(): void
    {
        $query = $this->getMockBuilder(MySQLQueryResult::class)
                      ->disableOriginalConstructor()
                      ->getMock();

        $query->expects($this->once())
              ->method('has_failed')
              ->willReturn(TRUE);

        $query->expects($this->once())
              ->method('has_deadlock')
              ->willReturn(TRUE);

        $query->expects($this->exactly(2))
              ->method('error_message')
              ->willReturn('message');

        $query->expects($this->exactly(1))
              ->method('error_number')
              ->willReturn(1);

        $query->expects($this->exactly(2))
              ->method('query')
              ->willReturn('query');

        $this->logger->expects($this->once())
             ->method('error')
             ->with('{query}; failed with error: {error}', [ 'query' => 'query', 'error' => 'message' ]);

        $this->expectException(DeadlockException::class);
        $this->expectExceptionMessage('Database query deadlock!');

        $this->class->verify_query_success($query);
    }

}

?>
