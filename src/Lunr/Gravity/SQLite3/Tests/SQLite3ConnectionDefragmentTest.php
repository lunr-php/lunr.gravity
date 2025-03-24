<?php

/**
 * This file contains the SQLite3ConnectionDefragmentTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2023 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\SQLite3\Tests;

use Lunr\Gravity\Exceptions\DefragmentationException;

/**
 * This class contains connection related unit tests for SQLite3Connection.
 *
 * @covers Lunr\Gravity\SQLite3\SQLite3Connection
 */
class SQLite3ConnectionDefragmentTest extends SQLite3ConnectionTestCase
{

    /**
     * Test that defragment() throws DefragmentationException if the query fails.
     *
     * @covers Lunr\Gravity\SQLite3\SQLite3Connection::defragment
     */
    public function testDefragmentThrowsExceptionIfQueryfails(): void
    {
        $this->setReflectionPropertyValue('connected', TRUE);

        $this->sqlite3->expects($this->once())
                      ->method('query')
                      ->with('VACUUM')
                      ->willReturn(FALSE);

        $this->logger->expects($this->once())
                     ->method('error')
                     ->with('{query}; failed with error: {error}');

        $this->expectException(DefragmentationException::class);
        $this->expectExceptionMessage('Database defragmentation failed.');

        $this->class->defragment();
    }

    /**
     * Tests the defragment succeeds.
     *
     * @covers Lunr\Gravity\SQLite3\SQLite3Connection::defragment
     */
    public function testDefragmentSucceeds(): void
    {
        $this->setReflectionPropertyValue('connected', TRUE);

        $this->sqlite3->expects($this->once())
                      ->method('query')
                      ->with('VACUUM')
                      ->willReturn($this->sqlite3Result);

        $this->class->defragment();
    }

}

?>
