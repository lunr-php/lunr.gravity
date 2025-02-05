<?php

/**
 * This file contains the MySQLConnectionDefragmentTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2023 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MySQL\Tests;

use Lunr\Gravity\Exceptions\DefragmentationException;
use Lunr\Gravity\MySQL\MySQLQueryEscaper;

/**
 * This class contains connection related unit tests for MySQLConnection.
 *
 * @covers Lunr\Gravity\MySQL\MySQLConnection
 */
class MySQLConnectionDefragmentTest extends MySQLConnectionTest
{

    /**
     * Test that defragment() throws DefragmentationException if the query fails.
     *
     * @covers Lunr\Gravity\MySQL\MySQLConnection::defragment
     */
    public function testDefragmentThrowsExceptionIfQueryfails(): void
    {
        $mysqli = new MockMySQLiSuccessfulConnection($this->getMockBuilder('\mysqli')->getMock());

        $escaper = $this->getMockBuilder(MySQLQueryEscaper::class)
                        ->disableOriginalConstructor()
                        ->getMock();

        $this->setReflectionPropertyValue('mysqli', $mysqli);
        $this->setReflectionPropertyValue('escaper', $escaper);
        $this->setReflectionPropertyValue('connected', TRUE);

        $escaper->expects($this->once())
                ->method('table')
                ->with('flights')
                ->willReturn('flights');

        $mysqli->expects($this->once())
               ->method('query')
               ->willReturn(FALSE);

        $this->mockFunction('mysqli_affected_rows', fn() => 0);

        $this->expectException(DefragmentationException::class);
        $this->expectExceptionMessage('Failed to optimize table: flights');

        $this->logger->expects($this->once())
                     ->method('error')
                     ->with('{query}; failed with error: {error}');

        $this->class->defragment('flights');

        $this->unmockFunction('mysqli_affected_rows');
    }

    /**
     * Tests the defragment succeeds.
     *
     * @requires extension mysqli
     * @covers Lunr\Gravity\MySQL\MySQLConnection::defragment
     */
    public function testDefragmentSucceeds(): void
    {
        $mysqli = new MockMySQLiSuccessfulConnection($this->getMockBuilder('\mysqli')->getMock());

        $escaper = $this->getMockBuilder(MySQLQueryEscaper::class)
                        ->disableOriginalConstructor()
                        ->getMock();

        $this->setReflectionPropertyValue('mysqli', $mysqli);
        $this->setReflectionPropertyValue('escaper', $escaper);
        $this->setReflectionPropertyValue('connected', TRUE);

        $escaper->expects($this->once())
                ->method('table')
                ->with('flights')
                ->willReturn('flights');

        $mysqli->expects($this->once())
               ->method('query')
               ->willReturn(TRUE);

        $this->mockFunction('mysqli_affected_rows', fn() => 0);

        $this->class->defragment('flights');

        $this->unmockFunction('mysqli_affected_rows');
    }

}

?>
