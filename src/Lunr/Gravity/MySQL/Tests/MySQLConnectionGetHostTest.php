<?php

/**
 * This file contains the MySQLConnectionGetHostTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2025 Framna Netherlands B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MySQL\Tests;

use MySQLi_Result;

/**
 * This class contains tests for the MySQLConnection class.
 *
 * @covers Lunr\Gravity\MySQL\MySQLConnection
 */
class MySQLConnectionGetHostTest extends MySQLConnectionTestCase
{

    /**
     * Test that getHost() returns NULL if the query returns TRUE.
     *
     * @covers Lunr\Gravity\MySQL\MySQLConnection::getHost
     */
    public function testGetHostWhenQueryReturnsTrue(): void
    {
        $mysqli = new MockMySQLiSuccessfulConnection($this->getMockBuilder('\mysqli')->getMock());

        $this->setReflectionPropertyValue('mysqli', $mysqli);

        $mysqli->expects($this->once())
               ->method('query')
               ->willReturn(TRUE);

        $method = $this->getReflectionMethod('getHost');

        $value = $method->invoke($this->class);

        $this->assertNull($value);
    }

    /**
     * Test that getHost() returns NULL if the query returns FALSE.
     *
     * @covers Lunr\Gravity\MySQL\MySQLConnection::getHost
     */
    public function testGetHostWhenQueryReturnsFalse(): void
    {
        $mysqli = new MockMySQLiSuccessfulConnection($this->getMockBuilder('\mysqli')->getMock());

        $this->setReflectionPropertyValue('mysqli', $mysqli);

        $mysqli->expects($this->once())
               ->method('query')
               ->willReturn(FALSE);

        $method = $this->getReflectionMethod('getHost');

        $value = $method->invoke($this->class);

        $this->assertNull($value);
    }

    /**
     * Test that getHost() returns NULL if fetching the query result fails.
     *
     * @covers Lunr\Gravity\MySQL\MySQLConnection::getHost
     */
    public function testGetHostWhenFetchingQueryResultFails(): void
    {
        $mysqli = new MockMySQLiSuccessfulConnection($this->getMockBuilder('\mysqli')->getMock());

        $this->setReflectionPropertyValue('mysqli', $mysqli);

        $result = $this->getMockBuilder(MySQLi_Result::class)
                       ->disableOriginalConstructor()
                       ->getMock();

        $result->expects($this->once())
               ->method('fetch_row')
               ->willReturn(FALSE);

        $mysqli->expects($this->once())
               ->method('query')
               ->willReturn($result);

        $method = $this->getReflectionMethod('getHost');

        $value = $method->invoke($this->class);

        $this->assertNull($value);
    }

    /**
     * Test that getHost() returns NULL if query returns no result.
     *
     * @covers Lunr\Gravity\MySQL\MySQLConnection::getHost
     */
    public function testGetHostWhenQueryReturnsNoResult(): void
    {
        $mysqli = new MockMySQLiSuccessfulConnection($this->getMockBuilder('\mysqli')->getMock());

        $this->setReflectionPropertyValue('mysqli', $mysqli);

        $result = $this->getMockBuilder(MySQLi_Result::class)
                       ->disableOriginalConstructor()
                       ->getMock();

        $result->expects($this->once())
               ->method('fetch_row')
               ->willReturn([]);

        $mysqli->expects($this->once())
               ->method('query')
               ->willReturn($result);

        $method = $this->getReflectionMethod('getHost');

        $value = $method->invoke($this->class);

        $this->assertNull($value);
    }

    /**
     * Test that getHost() returns query result.
     *
     * @covers Lunr\Gravity\MySQL\MySQLConnection::getHost
     */
    public function testGetHostWhenQuerySuccessful(): void
    {
        $mysqli = new MockMySQLiSuccessfulConnection($this->getMockBuilder('\mysqli')->getMock());

        $this->setReflectionPropertyValue('mysqli', $mysqli);

        $result = $this->getMockBuilder(MySQLi_Result::class)
                       ->disableOriginalConstructor()
                       ->getMock();

        $result->expects($this->once())
               ->method('fetch_row')
               ->willReturn([ 'db-server' ]);

        $mysqli->expects($this->once())
               ->method('query')
               ->willReturn($result);

        $method = $this->getReflectionMethod('getHost');

        $value = $method->invoke($this->class);

        $this->assertEquals('db-server', $value);
    }

}

?>
