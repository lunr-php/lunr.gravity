<?php

/**
 * This file contains the MySQLConnectionConnectTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2012 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MySQL\Tests;

use Throwable;

/**
 * This class contains connection related unit tests for MySQLConnection.
 *
 * @covers Lunr\Gravity\MySQL\MySQLConnection
 */
class MySQLConnectionConnectTest extends MySQLConnectionTest
{

    /**
     * Test a successful readonly connection.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\MySQL\MySQLConnection::connect
     */
    public function testSuccessfulConnectReadonly(): void
    {
        $mysqli = $this->getMockBuilder('Lunr\Gravity\MySQL\Tests\MockMySQLi')
                       ->setMethods([ 'connect', 'ssl_set', 'set_charset' ])
                       ->getMock();

        $this->set_reflection_property_value('mysqli', $mysqli);
        $this->set_reflection_property_value('readonly', TRUE);
        $this->set_reflection_property_value('ro_host', 'ro_host');

        $port   = ini_get('mysqli.default_port');
        $socket = ini_get('mysqli.default_socket');

        $mysqli->expects($this->once())
               ->method('connect')
               ->with('ro_host', 'username', 'password', 'database', $port, $socket);

        $mysqli->expects($this->never())
               ->method('ssl_set');

        $mysqli->expects($this->once())
               ->method('set_charset');

        $this->class->connect();

        $this->assertPropertyEquals('connected', TRUE);
        $this->set_reflection_property_value('connected', FALSE); //avoid deconstructor rollback
    }

    /**
     * Test a successful readwrite connection.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\MySQL\MySQLConnection::connect
     */
    public function testSuccessfulConnectReadwrite(): void
    {
        $mysqli = $this->getMockBuilder('Lunr\Gravity\MySQL\Tests\MockMySQLi')
                       ->setMethods([ 'connect', 'ssl_set', 'set_charset' ])
                       ->getMock();

        $this->set_reflection_property_value('mysqli', $mysqli);

        $port   = ini_get('mysqli.default_port');
        $socket = ini_get('mysqli.default_socket');

        $mysqli->expects($this->once())
               ->method('connect')
               ->with('rw_host', 'username', 'password', 'database', $port, $socket);

        $mysqli->expects($this->never())
               ->method('ssl_set');

        $mysqli->expects($this->once())
               ->method('set_charset');

        $this->class->connect();

        $this->assertPropertyEquals('connected', TRUE);
        $this->set_reflection_property_value('connected', FALSE); //avoid deconstructor rollback
    }

    /**
     * Test a successful readwrite connection with ssl.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\MySQL\MySQLConnection::connect
     */
    public function testSuccessfulConnectReadwriteWithSSL(): void
    {
        $mysqli = $this->getMockBuilder('Lunr\Gravity\MySQL\Tests\MockMySQLi')
                       ->setMethods([ 'connect', 'ssl_set', 'set_charset' ])
                       ->getMock();

        $this->set_reflection_property_value('mysqli', $mysqli);

        $port   = ini_get('mysqli.default_port');
        $socket = ini_get('mysqli.default_socket');

        $this->set_reflection_property_value('ssl_key', 'ssl_key');
        $this->set_reflection_property_value('ssl_cert', 'ssl_cert');
        $this->set_reflection_property_value('ca_cert', 'ca_cert');
        $this->set_reflection_property_value('ca_path', 'ca_path');
        $this->set_reflection_property_value('cipher', 'cipher');

        $mysqli->expects($this->once())
               ->method('connect')
               ->with('rw_host', 'username', 'password', 'database', $port, $socket);

        $mysqli->expects($this->once())
               ->method('ssl_set')
               ->with('ssl_key', 'ssl_cert', 'ca_cert', 'ca_path', 'cipher');

        $mysqli->expects($this->once())
               ->method('set_charset');

        $this->class->connect();

        $this->assertPropertyEquals('connected', TRUE);
        $this->set_reflection_property_value('connected', FALSE); //avoid deconstructor rollback
    }

    /**
     * Test a successful readwrite connection with ssl.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\MySQL\MySQLConnection::connect
     */
    public function testSuccessfulReconnect(): void
    {
        $mysqli = $this->getMockBuilder('Lunr\Gravity\MySQL\Tests\MockMySQLi')
                       ->setMethods([ 'connect', 'ssl_set', 'set_charset', 'close' ])
                       ->getMock();

        $this->set_reflection_property_value('mysqli', $mysqli);

        $port   = ini_get('mysqli.default_port');
        $socket = ini_get('mysqli.default_socket');

        $this->set_reflection_property_value('ssl_key', 'ssl_key');
        $this->set_reflection_property_value('ssl_cert', 'ssl_cert');
        $this->set_reflection_property_value('ca_cert', 'ca_cert');
        $this->set_reflection_property_value('ca_path', 'ca_path');
        $this->set_reflection_property_value('cipher', 'cipher');

        $mysqli->expects($this->exactly(5))
               ->method('connect')
               ->with('rw_host', 'username', 'password', 'database', $port, $socket);

        $mysqli->expects($this->exactly(5))
               ->method('ssl_set')
               ->with('ssl_key', 'ssl_cert', 'ca_cert', 'ca_path', 'cipher');

        $mysqli->expects($this->exactly(4))
               ->method('close');

        $mysqli->expects($this->exactly(5))
               ->method('set_charset')
               ->willReturnOnConsecutiveCalls(FALSE, FALSE, FALSE, FALSE, TRUE);

        $this->class->connect();

        $this->assertPropertyEquals('connected', TRUE);
        $this->set_reflection_property_value('connected', FALSE); //avoid deconstructor rollback
    }

    /**
     * Test a failed connection attempt.
     *
     * @covers Lunr\Gravity\MySQL\MySQLConnection::connect
     */
    public function testFailedConnect(): void
    {
        $mysqli = new MockMySQLiFailedConnection($this->getMockBuilder('\mysqli')->getMock());

        $this->set_reflection_property_value('mysqli', $mysqli);

        $mysqli->expects($this->never())
               ->method('ssl_set');

        $mysqli->expects($this->any())
               ->method('options');

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
            $this->assertPropertyEquals('connected', FALSE); //avoid deconstructor rollback
        }
    }

    /**
     * Test a failed connection attempt.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\MySQL\MySQLConnection::connect
     */
    public function testUnsuccessfulReconnect(): void
    {
        $mysqli = $this->getMockBuilder('Lunr\Gravity\MySQL\Tests\MockMySQLi')
                       ->setMethods([ 'connect', 'ssl_set', 'set_charset', 'close' ])
                       ->getMock();

        $this->set_reflection_property_value('mysqli', $mysqli);

        $port   = ini_get('mysqli.default_port');
        $socket = ini_get('mysqli.default_socket');

        $this->set_reflection_property_value('ssl_key', 'ssl_key');
        $this->set_reflection_property_value('ssl_cert', 'ssl_cert');
        $this->set_reflection_property_value('ca_cert', 'ca_cert');
        $this->set_reflection_property_value('ca_path', 'ca_path');
        $this->set_reflection_property_value('cipher', 'cipher');

        $mysqli->expects($this->exactly(5))
               ->method('connect')
               ->with('rw_host', 'username', 'password', 'database', $port, $socket);

        $mysqli->expects($this->exactly(5))
               ->method('ssl_set')
               ->with('ssl_key', 'ssl_cert', 'ca_cert', 'ca_path', 'cipher');

        $mysqli->expects($this->exactly(5))
               ->method('close');

        $mysqli->expects($this->exactly(5))
               ->method('set_charset')
               ->willReturnOnConsecutiveCalls(FALSE, FALSE, FALSE, FALSE, FALSE);

        $this->expectException('Lunr\Gravity\Exceptions\ConnectionException');
        $this->expectExceptionMessage('Could not establish connection to the database! Exceeded reconnect count!');

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
            $this->assertPropertyEquals('connected', FALSE);
        }
    }

    /**
     * Test that connect() does not reconnect when we are already connected.
     *
     * @covers Lunr\Gravity\MySQL\MySQLConnection::connect
     */
    public function testConnectDoesNotReconnectWhenAlreadyConnected(): void
    {
        $this->set_reflection_property_value('connected', TRUE);

        $this->mysqli->expects($this->never())
                     ->method('connect');

        $this->mysqli->expects($this->never())
                     ->method('ssl_set');

        $this->mysqli->expects($this->any())
                     ->method('options');

        $this->class->connect();

        $this->assertPropertyEquals('connected', TRUE);
    }

    /**
     * Test that connect() fails when the driver specified is not mysql.
     *
     * @covers Lunr\Gravity\MySQL\MySQLConnection::connect
     */
    public function testConnectFailsWhenDriverIsNotMysql(): void
    {
        $sub_configuration = $this->getMockBuilder('Lunr\Core\Configuration')->getMock();

        $configuration = $this->getMockBuilder('Lunr\Core\Configuration')->getMock();

        $map = [ [ 'db', $sub_configuration ] ];

        $configuration->expects($this->any())
                      ->method('offsetGet')
                      ->will($this->returnValueMap($map));

        $map = [
            [ 'rw_host', 'rw_host' ],
            [ 'username', 'username' ],
            [ 'password', 'password' ],
            [ 'database', 'database' ],
            [ 'driver', 'not_mysql' ],
        ];

        $sub_configuration->expects($this->any())
                          ->method('offsetGet')
                          ->will($this->returnValueMap($map));

        $this->mysqli->expects($this->never())
                     ->method('ssl_set');

        $this->set_reflection_property_value('configuration', $configuration);

        $this->mysqli->expects($this->any())
                     ->method('options');

        $this->expectException('Lunr\Gravity\Exceptions\ConnectionException');
        $this->expectExceptionMessage('Cannot connect to a non-mysql database connection!');

        $this->class->connect();
    }

    /**
     * Test that disconnect() does not try to disconnect when we are not connected yet.
     *
     * @covers Lunr\Gravity\MySQL\MySQLConnection::disconnect
     */
    public function testDisconnectDoesNotTryToDisconnectWhenNotConnected(): void
    {
        $this->mysqli->expects($this->never())
                     ->method('close');

        $this->class->disconnect();

        $this->assertPropertyEquals('connected', FALSE);
    }

    /**
     * Test that disconnect() works correctly.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\MySQL\MySQLConnection::disconnect
     */
    public function testDisconnect(): void
    {
        $mysqli = new MockMySQLiSuccessfulConnection($this->getMockBuilder('\mysqli')->getMock());

        $this->set_reflection_property_value('mysqli', $mysqli);

        $mysqli->expects($this->any())
               ->method('options');

        $this->class->connect();

        $this->assertPropertyEquals('connected', TRUE);

        $this->class->disconnect();

        $this->assertPropertyEquals('connected', FALSE);
    }

    /**
     * Test that change_database() throws an exception when we couldn't connect.
     *
     * @covers Lunr\Gravity\MySQL\MySQLConnection::change_database
     */
    public function testChangeDatabaseThrowsExceptionWhenNotConnected(): void
    {
        $mysqli = new MockMySQLiFailedConnection($this->getMockBuilder('\mysqli')->getMock());

        $this->set_reflection_property_value('mysqli', $mysqli);

        $this->expectException('Lunr\Gravity\Exceptions\ConnectionException');
        $this->expectExceptionMessage('Could not establish connection to the database!');

        $this->class->change_database('new_db');
    }

    /**
     * Test that change_database() returns FALSE when select_db() fails.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\MySQL\MySQLConnection::change_database
     */
    public function testChangeDatabaseReturnsFalseWhenSelectDBFailed(): void
    {
        $this->set_reflection_property_value('connected', TRUE);

        $this->mysqli->expects($this->once())
                     ->method('select_db')
                     ->will($this->returnValue(FALSE));

        $this->assertFalse($this->class->change_database('new_db'));

        $this->set_reflection_property_value('connected', FALSE); //avoid deconstructor rollback
    }

    /**
     * Test that change_database() returns TRUE when select_db() works.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\MySQL\MySQLConnection::change_database
     */
    public function testChangeDatabaseReturnsTrueWhenSelectDBWorked(): void
    {
        $this->set_reflection_property_value('connected', TRUE);

        $this->mysqli->expects($this->once())
                     ->method('select_db')
                     ->will($this->returnValue(TRUE));

        $this->assertTrue($this->class->change_database('new_db'));

        $this->set_reflection_property_value('connected', FALSE); //avoid deconstructor rollback
    }

    /**
     * Test that options() works.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\MySQL\MySQLConnection::set_option
     */
    public function testOptionsWorks(): void
    {
        $this->class->set_option(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, FALSE);
        $this->class->set_option(MYSQLI_OPT_CONNECT_TIMEOUT, 42);

        $this->assertPropertyEquals('options', [ MYSQLI_OPT_INT_AND_FLOAT_NATIVE => FALSE, MYSQLI_OPT_CONNECT_TIMEOUT => 42 ]);
    }

}

?>
