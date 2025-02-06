<?php

/**
 * This file contains the MySQLConnectionTransactionTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2012 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MySQL\Tests;

/**
 * This class contains transaction related unit tests for MySQLConnection.
 *
 * @covers Lunr\Gravity\MySQL\MySQLConnection
 */
class MySQLConnectionTransactionTest extends MySQLConnectionTestCase
{

    /**
     * Test a successful call to Begin Transaction.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\MySQL\MySQLConnection::begin_transaction
     */
    public function testBeginTransactionStartsTransactionWhenConnected(): void
    {
        $property = $this->getReflectionProperty('connected');
        $property->setValue($this->class, TRUE);

        $this->mysqli->expects($this->once())
                     ->method('autocommit')
                     ->will($this->returnValue(TRUE));

        $this->assertTrue($this->class->begin_transaction());

        $property->setValue($this->class, FALSE);
    }

    /**
     * Test a call to Begin Transaction with no connection.
     *
     * @covers Lunr\Gravity\MySQL\MySQLConnection::begin_transaction
     */
    public function testBeginTransactionThrowsExceptionWhenNotConnected(): void
    {
        $mysqli = new MockMySQLiFailedConnection($this->getMockBuilder('\mysqli')->getMock());

        $this->setReflectionPropertyValue('mysqli', $mysqli);

        $this->expectException('Lunr\Gravity\Exceptions\ConnectionException');
        $this->expectExceptionMessage('Could not establish connection to the database!');

        $this->class->begin_transaction();
    }

    /**
     * Test a successful call to Commit.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\MySQL\MySQLConnection::commit
     */
    public function testCommitWhenConnected(): void
    {
        $property = $this->getReflectionProperty('connected');
        $property->setValue($this->class, TRUE);

        $this->mysqli->expects($this->once())
                     ->method('commit')
                     ->will($this->returnValue(TRUE));

        $this->assertTrue($this->class->commit());

        $property->setValue($this->class, FALSE);
    }

    /**
     * Test a call to Commit with no connection.
     *
     * @covers Lunr\Gravity\MySQL\MySQLConnection::commit
     */
    public function testCommitThrowsExceptionWhenNotConnected(): void
    {
        $mysqli = new MockMySQLiFailedConnection($this->getMockBuilder('\mysqli')->getMock());

        $this->setReflectionPropertyValue('mysqli', $mysqli);

        $this->expectException('Lunr\Gravity\Exceptions\ConnectionException');
        $this->expectExceptionMessage('Could not establish connection to the database!');

        $this->class->commit();
    }

    /**
     * Test a successful call to rollback.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\MySQL\MySQLConnection::rollback
     */
    public function testRollbackWhenConnected(): void
    {
        $property = $this->getReflectionProperty('connected');
        $property->setValue($this->class, TRUE);

        $this->mysqli->expects($this->once())
                     ->method('rollback')
                     ->will($this->returnValue(TRUE));

        $this->assertTrue($this->class->rollback());

        $property->setValue($this->class, FALSE);
    }

    /**
     * Test a call to Commit with no connection.
     *
     * @covers Lunr\Gravity\MySQL\MySQLConnection::commit
     */
    public function testRollbackThrowsExceptionWhenNotConnected(): void
    {
        $mysqli = new MockMySQLiFailedConnection($this->getMockBuilder('\mysqli')->getMock());

        $this->setReflectionPropertyValue('mysqli', $mysqli);

        $this->expectException('Lunr\Gravity\Exceptions\ConnectionException');
        $this->expectExceptionMessage('Could not establish connection to the database!');

        $this->class->rollback();
    }

    /**
     * Test a successful call to rollback.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\MySQL\MySQLConnection::rollback
     */
    public function testEndTransactionWhenConnected(): void
    {
        $property = $this->getReflectionProperty('connected');
        $property->setValue($this->class, TRUE);

        $this->mysqli->expects($this->once())
                     ->method('autocommit')
                     ->will($this->returnValue(TRUE));

        $this->assertTrue($this->class->end_transaction());

        $property->setValue($this->class, FALSE);
    }

    /**
     * Test a call to Commit with no connection.
     *
     * @covers Lunr\Gravity\MySQL\MySQLConnection::commit
     */
    public function testEndTransactionThrowsExceptionWhenNotConnected(): void
    {
        $mysqli = new MockMySQLiFailedConnection($this->getMockBuilder('\mysqli')->getMock());

        $this->setReflectionPropertyValue('mysqli', $mysqli);

        $this->expectException('Lunr\Gravity\Exceptions\ConnectionException');
        $this->expectExceptionMessage('Could not establish connection to the database!');

        $this->class->end_transaction();
    }

}
?>
