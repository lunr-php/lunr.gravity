<?php

/**
 * This file contains the SQLite3AccessObjectBaseTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2023 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\SQLite3\Tests;

use Lunr\Gravity\SQLite3\SQLite3Connection;
use Lunr\Gravity\SQLite3\SQLite3QueryEscaper;
use Lunr\Halo\PropertyTraits\PsrLoggerTestTrait;

/**
 * This class contains the tests for the SQLite3AccessObject class.
 *
 * Base tests for the case where there is no DatabaseConnectionPool.
 *
 * @covers Lunr\Gravity\SQLite3\SQLite3AccessObject
 */
class SQLite3AccessObjectBaseTest extends SQLite3AccessObjectTestCase
{

    use PsrLoggerTestTrait;

    /**
     * Test that DatabaseConnection class is passed.
     */
    public function testSQLite3ConnectionIsPassed(): void
    {
        $this->assertPropertySame('db', $this->db);
    }

    /**
     * Test that the Escaper is stored.
     */
    public function testQueryEscaperIsStored(): void
    {
        $property = $this->getReflectionPropertyValue('escaper');

        $this->assertInstanceOf(SQLite3QueryEscaper::class, $property);
    }

    /**
     * Test that swap_generic_connection() swaps database connections.
     *
     * @covers Lunr\Gravity\SQLite3\SQLite3AccessObject::swap_connection
     */
    public function testSwapConnectionSwapsConnection(): void
    {
        $db = $this->getMockBuilder(SQLite3Connection::class)
                   ->disableOriginalConstructor()
                   ->getMock();

        $escaper = $this->getMockBuilder(SQLite3QueryEscaper::class)
                        ->disableOriginalConstructor()
                        ->getMock();

        $property = $this->getReflectionProperty('db');

        $parent = $this->reflection->getParentClass();

        $parentProperty = $parent->getProperty('db');
        $parentProperty->setAccessible(TRUE);

        $this->assertNotSame($db, $property->getValue($this->class));
        $this->assertNotSame($db, $parentProperty->getValue($this->class));

        $db->expects($this->once())
           ->method('get_query_escaper_object')
           ->willReturn($escaper);

        $this->class->swap_connection($db);

        $this->assertSame($db, $property->getValue($this->class));
        $this->assertSame($db, $parentProperty->getValue($this->class));
    }

    /**
     * Test that swap_generic_connection() swaps query escaper.
     *
     * @covers Lunr\Gravity\SQLite3\SQLite3AccessObject::swap_connection
     */
    public function testSwapConnectionSwapsQueryEscaper(): void
    {
        $db = $this->getMockBuilder(SQLite3Connection::class)
                   ->disableOriginalConstructor()
                   ->getMock();

        $escaper = $this->getMockBuilder(SQLite3QueryEscaper::class)
                        ->disableOriginalConstructor()
                        ->getMock();

        $property = $this->getReflectionProperty('escaper');

        $old = $property->getValue($this->class);

        $db->expects($this->once())
           ->method('get_query_escaper_object')
           ->willReturn($escaper);

        $this->class->swap_connection($db);

        $new = $property->getValue($this->class);

        $this->assertNotSame($old, $new);
        $this->assertInstanceOf(SQLite3QueryEscaper::class, $new);
    }

}

?>
