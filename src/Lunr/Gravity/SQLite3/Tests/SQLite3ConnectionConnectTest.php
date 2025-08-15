<?php

/**
 * This file contains the SQLite3ConnectionConnectTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2013 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\SQLite3\Tests;

use Throwable;

/**
 * This class contains connection related unit tests for SQLite3Connection.
 *
 * @covers Lunr\Gravity\SQLite3\SQLite3Connection
 */
class SQLite3ConnectionConnectTest extends SQLite3ConnectionTestCase
{

    /**
     * Test a successful readonly connection.
     *
     * @covers Lunr\Gravity\SQLite3\SQLite3Connection::connect
     */
    public function testSuccessfulConnectReadonly(): void
    {
        $this->setReflectionPropertyValue('readonly', TRUE);

        $this->sqlite3->expects($this->once())
                      ->method('open')
                      ->with('/tmp/test.db', SQLITE3_OPEN_READONLY | SQLITE3_OPEN_CREATE, '');

        $this->sqlite3->expects($this->once())
                      ->method('lastErrorCode')
                      ->willReturn(0);

        $this->class->connect();

        $this->assertTrue($this->getReflectionPropertyValue('connected'));
    }

    /**
     * Test a successful readwrite connection.
     *
     * @covers Lunr\Gravity\SQLite3\SQLite3Connection::connect
     */
    public function testSuccessfulConnectReadwrite(): void
    {
        $this->sqlite3->expects($this->once())
                      ->method('open')
                      ->with('/tmp/test.db', SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE, '');

        $this->sqlite3->expects($this->once())
                      ->method('lastErrorCode')
                      ->willReturn(0);

        $this->class->connect();

        $this->assertTrue($this->getReflectionPropertyValue('connected'));
    }

    /**
     * Test a failed connection attempt.
     *
     * @covers Lunr\Gravity\SQLite3\SQLite3Connection::connect
     */
    public function testFailedConnect(): void
    {
        $this->setReflectionPropertyValue('readonly', TRUE);

        $this->sqlite3->expects($this->once())
                      ->method('open')
                      ->with('/tmp/test.db', SQLITE3_OPEN_READONLY | SQLITE3_OPEN_CREATE, '');

        $this->sqlite3->expects($this->once())
                      ->method('lastErrorCode')
                      ->willReturn(1);

        $this->expectException('Lunr\Gravity\Exceptions\ConnectionException');
        $this->expectExceptionMessage('Could not establish connection to the database!');

        try
        {
            $this->class->connect();
        }
        catch (Throwable $e)
        {
            throw $e;
        }
        finally
        {
            $this->assertFalse($this->getReflectionPropertyValue('connected'));
        }
    }

    /**
     * Test that connect() does not reconnect when we are already connected.
     *
     * @covers Lunr\Gravity\SQLite3\SQLite3Connection::connect
     */
    public function testConnectDoesNotReconnectWhenAlreadyConnected(): void
    {
        $this->setReflectionPropertyValue('connected', TRUE);

        $this->sqlite3->expects($this->never())
                      ->method('open');

        $this->class->connect();

        $this->assertTrue($this->getReflectionPropertyValue('connected'));
    }

    /**
     * Test that connect() throws an exception when the driver specified is not sqlite3.
     *
     * @covers Lunr\Gravity\SQLite3\SQLite3Connection::connect
     */
    public function testConnectThrowsExceptionWhenDriverIsNotSQLite3()
    {
        $subConfiguration = $this->getMockBuilder('Lunr\Core\Configuration')->getMock();

        $configuration = $this->getMockBuilder('Lunr\Core\Configuration')->getMock();

        $map = [
            [ 'db', $subConfiguration ],
        ];

        $configuration->expects($this->any())
                      ->method('offsetGet')
                      ->will($this->returnValueMap($map));

        $map = [
            [ 'file', '/tmp/test.db' ],
            [ 'driver', 'not_sqlite3' ],
        ];

        $subConfiguration->expects($this->any())
                         ->method('offsetGet')
                         ->will($this->returnValueMap($map));

        $this->setReflectionPropertyValue('config', $configuration);

        $this->expectException('Lunr\Gravity\Exceptions\ConnectionException');
        $this->expectExceptionMessage('Cannot connect to a non-sqlite3 database connection!');

        $this->class->connect();
    }

    /**
     * Test that disconnect() does not try to disconnect when we are not connected yet.
     *
     * @covers Lunr\Gravity\SQLite3\SQLite3Connection::disconnect
     */
    public function testDisconnectDoesNotTryToDisconnectWhenNotConnected(): void
    {
        $this->setReflectionPropertyValue('connected', FALSE);

        $this->sqlite3->expects($this->never())
                      ->method('close');

        $this->class->disconnect();

        $this->assertFalse($this->getReflectionPropertyValue('connected'));
    }

    /**
     * Test that disconnect() works correctly.
     *
     * @covers Lunr\Gravity\SQLite3\SQLite3Connection::disconnect
     */
    public function testDisconnect(): void
    {
        $this->setReflectionPropertyValue('connected', TRUE);

        $this->sqlite3->expects($this->once())
                      ->method('close');

        $this->class->disconnect();

        $this->assertFalse($this->getReflectionPropertyValue('connected'));
    }

    /**
     * Test that change_database() returns TRUE when connected.
     *
     * @covers Lunr\Gravity\SQLite3\SQLite3Connection::change_database
     */
    public function testChangeDatabaseReturnsTrueWhenConnected(): void
    {
        $this->setReflectionPropertyValue('connected', TRUE);

        $this->sqlite3->expects($this->once())
                      ->method('open')
                      ->with('new_db', SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE, '');

        $this->sqlite3->expects($this->once())
                      ->method('lastErrorCode')
                      ->willReturn(0);

        $return = $this->class->change_database('new_db');

        $this->assertEquals('new_db', $this->getReflectionPropertyValue('db'));

        $this->assertTrue($return);
    }

    /**
     * Test that change_database() throws an exception when we couldn't connect.
     *
     * @covers Lunr\Gravity\SQLite3\SQLite3Connection::change_database
     */
    public function testChangeDatabaseThrowsExceptionWhenNotConnected(): void
    {
        $this->setReflectionPropertyValue('connected', TRUE);

        $this->sqlite3->expects($this->once())
                      ->method('open')
                      ->with('new_db', SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE, '');

        $this->sqlite3->expects($this->once())
                      ->method('lastErrorCode')
                      ->willReturn(1);

        $this->expectException('Lunr\Gravity\Exceptions\ConnectionException');
        $this->expectExceptionMessage('Could not establish connection to the database!');

        try
        {
            $this->class->change_database('new_db');
        }
        catch (Throwable $e)
        {
            throw $e;
        }
        finally
        {
            $this->assertEquals('new_db', $this->getReflectionPropertyValue('db'));
        }
    }

}

?>
