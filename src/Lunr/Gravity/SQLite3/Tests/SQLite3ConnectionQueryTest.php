<?php

/**
 * This file contains the SQLite3ConnectionQueryTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2013 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\SQLite3\Tests;

/**
 * This class contains query related unit tests for SQLite3Connection.
 *
 * @covers Lunr\Gravity\SQLite3\SQLite3Connection
 */
class SQLite3ConnectionQueryTest extends SQLite3ConnectionTestCase
{

    /**
     * Test that query() throws an exception when not connected.
     *
     * @covers Lunr\Gravity\SQLite3\SQLite3Connection::query
     */
    public function testQueryThrowsExceptionWhenNotConnected(): void
    {
        $this->sqlite3->expects($this->once())
                      ->method('open');

        $this->sqlite3->expects($this->once())
                      ->method('lastErrorCode')
                      ->willReturn(1);

        $this->expectException('Lunr\Gravity\Exceptions\ConnectionException');
        $this->expectExceptionMessage('Could not establish connection to the database!');

        $this->class->query('query');
    }

    /**
     * Test that query() returns a QueryResult when connected.
     *
     * @covers   Lunr\Gravity\SQLite3\SQLite3Connection::query
     */
    public function testQueryReturnsQueryResultWhenConnected(): void
    {
        $this->setReflectionPropertyValue('connected', TRUE);

        $this->sqlite3->expects($this->once())
                      ->method('query')
                      ->will($this->returnValue($this->sqlite3Result));

        $query = $this->class->query('query');

        $this->assertInstanceOf('Lunr\Gravity\SQLite3\SQLite3QueryResult', $query);
        $this->assertFalse($query->has_failed());
    }

}

?>
