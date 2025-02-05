<?php

/**
 * This file contains the MariaDBAccessObjectBaseTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2023 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MariaDB\Tests;

use Lunr\Gravity\MariaDB\MariaDBConnection;
use Lunr\Gravity\MySQL\MySQLQueryEscaper;
use Lunr\Halo\PropertyTraits\PsrLoggerTestTrait;

/**
 * This class contains the tests for the MariaDBAccessObject class.
 *
 * Base tests for the case where there is no DatabaseConnectionPool.
 *
 * @covers Lunr\Gravity\MariaDB\MariaDBAccessObject
 */
class MariaDBAccessObjectBaseTest extends MariaDBAccessObjectTestCase
{

    use PsrLoggerTestTrait;

    /**
     * Test that DatabaseConnection class is passed.
     */
    public function testMariaDBConnectionIsPassed(): void
    {
        $this->assertPropertySame('db', $this->db);
    }

    /**
     * Test that the Escaper is stored.
     */
    public function testQueryEscaperIsStored(): void
    {
        $property = $this->getReflectionPropertyValue('escaper');

        $this->assertInstanceOf(MySQLQueryEscaper::class, $property);
    }

    /**
     * Test that swap_generic_connection() swaps database connections.
     *
     * @covers Lunr\Gravity\MariaDB\MariaDBAccessObject::swap_connection
     */
    public function testSwapConnectionSwapsConnection(): void
    {
        $db = $this->getMockBuilder(MariaDBConnection::class)
                   ->disableOriginalConstructor()
                   ->getMock();

        $escaper = $this->getMockBuilder(MySQLQueryEscaper::class)
                        ->disableOriginalConstructor()
                        ->getMock();

        $property = $this->getReflectionProperty('db');

        $parent = $this->reflection->getParentClass();

        $parent_property = $parent->getProperty('db');
        $parent_property->setAccessible(TRUE);

        $this->assertNotSame($db, $property->getValue($this->class));
        $this->assertNotSame($db, $parent_property->getValue($this->class));

        $db->expects($this->once())
           ->method('get_query_escaper_object')
           ->will($this->returnValue($escaper));

        $this->class->swap_connection($db);

        $this->assertSame($db, $property->getValue($this->class));
        $this->assertSame($db, $parent_property->getValue($this->class));
    }

    /**
     * Test that swap_generic_connection() swaps query escaper.
     *
     * @covers Lunr\Gravity\MariaDB\MariaDBAccessObject::swap_connection
     */
    public function testSwapConnectionSwapsQueryEscaper(): void
    {
        $db = $this->getMockBuilder(MariaDBConnection::class)
                   ->disableOriginalConstructor()
                   ->getMock();

        $escaper = $this->getMockBuilder(MySQLQueryEscaper::class)
                        ->disableOriginalConstructor()
                        ->getMock();

        $property = $this->getReflectionProperty('escaper');

        $old = $property->getValue($this->class);

        $db->expects($this->once())
           ->method('get_query_escaper_object')
           ->will($this->returnValue($escaper));

        $this->class->swap_connection($db);

        $new = $property->getValue($this->class);

        $this->assertNotSame($old, $new);
        $this->assertInstanceOf(MySQLQueryEscaper::class, $new);
    }

}

?>
