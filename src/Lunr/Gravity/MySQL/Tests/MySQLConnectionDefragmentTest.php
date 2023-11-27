<?php

/**
 * This file contains the MySQLConnectionDefragmentTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2023 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MySQL\Tests;

use Lunr\Gravity\Exceptions\DefragmentationException;
use Lunr\Gravity\DatabaseQueryEscaper;

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

        $escaper = $this->getMockBuilder(DatabaseQueryEscaper::class)
                        ->disableOriginalConstructor()
                        ->getMock();

        $this->set_reflection_property_value('mysqli', $mysqli);
        $this->set_reflection_property_value('escaper', $escaper);
        $this->set_reflection_property_value('connected', TRUE);

        $escaper->expects($this->once())
                ->method('table')
                ->with('flights')
                ->willReturn('flights');

        $mysqli->expects($this->once())
               ->method('query')
               ->willReturn(FALSE);

        $this->mock_function('mysqli_affected_rows', fn() => 0);

        $this->expectException(DefragmentationException::class);
        $this->expectExceptionMessage('Failed to optimize table: flights');

        $this->logger->expects($this->once())
                     ->method('error')
                     ->with('{query}; failed with error: {error}');

        $this->class->defragment('flights');

        $this->unmock_function('mysqli_affected_rows');
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

        $escaper = $this->getMockBuilder(DatabaseQueryEscaper::class)
                        ->disableOriginalConstructor()
                        ->getMock();

        $this->set_reflection_property_value('mysqli', $mysqli);
        $this->set_reflection_property_value('escaper', $escaper);
        $this->set_reflection_property_value('connected', TRUE);

        $escaper->expects($this->once())
                ->method('table')
                ->with('flights')
                ->willReturn('flights');

        $mysqli->expects($this->once())
               ->method('query')
               ->willReturn(TRUE);

        $this->mock_function('mysqli_affected_rows', fn() => 0);

        $this->class->defragment('flights');

        $this->unmock_function('mysqli_affected_rows');
    }

}

?>
