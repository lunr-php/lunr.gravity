<?php

/**
 * This file contains the DatabaseConnectionPoolSupportedTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2012 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\Tests;

use ReflectionClass;

/**
 * This class contains tests for the DatabaseConnectionPool class.
 * Specifically for the case when there is a supported database configuration present.
 *
 * @covers Lunr\Gravity\DatabaseConnectionPool
 */
class DatabaseConnectionPoolSupportedTest extends DatabaseConnectionPoolTestCase
{

    /**
     * TestCase Constructor.
     */
    public function setUp(): void
    {
        $this->supportedSetup();
    }

    /**
     * Test that get_connection() returns a new MySQLConnection.
     *
     * @covers Lunr\Gravity\DatabaseConnectionPool::get_connection
     */
    public function testGetNewAndReadonlyConnectionReturnsMysqlConnection(): void
    {
        $method = $this->poolReflection->getMethod('get_connection');
        $method->setAccessible(TRUE);

        $dbr = new ReflectionClass('Lunr\Gravity\MySQL\MySQLConnection');

        $property = $dbr->getProperty('readonly');
        $property->setAccessible(TRUE);

        $value = $method->invokeArgs($this->pool, [ TRUE, TRUE ]);

        $this->assertInstanceOf('Lunr\Gravity\MySQL\MySQLConnection', $value);
        $this->assertTrue($property->getValue($value));
    }

    /**
     * Test that get_connection() returns a new MySQLConnection.
     *
     * @covers Lunr\Gravity\DatabaseConnectionPool::get_connection
     */
    public function testGetNewAndReadWriteConnectionReturnsMysqlConnection(): void
    {
        $method = $this->poolReflection->getMethod('get_connection');
        $method->setAccessible(TRUE);

        $dbr = new ReflectionClass('Lunr\Gravity\MySQL\MySQLConnection');

        $property = $dbr->getProperty('readonly');
        $property->setAccessible(TRUE);

        $value = $method->invokeArgs($this->pool, [ TRUE, FALSE ]);

        $this->assertInstanceOf('Lunr\Gravity\MySQL\MySQLConnection', $value);
        $this->assertFalse($property->getValue($value));
    }

    /**
     * Test that get_connection() populates pool with new connections.
     *
     * @covers Lunr\Gravity\DatabaseConnectionPool::get_connection
     */
    public function testGetNewAndReadonlyConnectionIncreasesPoolByOne(): void
    {
        $method = $this->poolReflection->getMethod('get_connection');
        $method->setAccessible(TRUE);

        $property = $this->poolReflection->getProperty('roPool');
        $property->setAccessible(TRUE);

        $this->assertEmpty($property->getValue($this->pool));

        $value = $method->invokeArgs($this->pool, [ TRUE, TRUE ]);

        $stored = $property->getValue($this->pool);

        $this->assertCount(1, $stored);
        $this->assertSame($value, $stored[0]);
    }

    /**
     * Test that get_connection() populates pool with new connections.
     *
     * @covers Lunr\Gravity\DatabaseConnectionPool::get_connection
     */
    public function testGetNewAndReadWriteConnectionIncreasesPoolByOne(): void
    {
        $method = $this->poolReflection->getMethod('get_connection');
        $method->setAccessible(TRUE);

        $property = $this->poolReflection->getProperty('rwPool');
        $property->setAccessible(TRUE);

        $this->assertEmpty($property->getValue($this->pool));

        $value = $method->invokeArgs($this->pool, [ TRUE, FALSE ]);

        $stored = $property->getValue($this->pool);

        $this->assertCount(1, $stored);
        $this->assertSame($value, $stored[0]);
    }

    /**
     * Test that get_connection() populates the pool if it is empty.
     *
     * @covers Lunr\Gravity\DatabaseConnectionPool::get_connection
     */
    public function testGetReadonlyConnectionReturnsNewConnectionIfPoolEmpty(): void
    {
        $method = $this->poolReflection->getMethod('get_connection');
        $method->setAccessible(TRUE);

        $property = $this->poolReflection->getProperty('roPool');
        $property->setAccessible(TRUE);

        $this->assertEmpty($property->getValue($this->pool));

        $value = $method->invokeArgs($this->pool, [ FALSE, TRUE ]);

        $stored = $property->getValue($this->pool);

        $this->assertCount(1, $stored);
        $this->assertSame($value, $stored[0]);
    }

    /**
     * Test that get_connection() populates the pool if it is empty.
     *
     * @covers Lunr\Gravity\DatabaseConnectionPool::get_connection
     */
    public function testGetReadWriteConnectionReturnsNewConnectionIfPoolEmpty(): void
    {
        $method = $this->poolReflection->getMethod('get_connection');
        $method->setAccessible(TRUE);

        $property = $this->poolReflection->getProperty('rwPool');
        $property->setAccessible(TRUE);

        $this->assertEmpty($property->getValue($this->pool));

        $value = $method->invokeArgs($this->pool, [ FALSE, FALSE ]);

        $stored = $property->getValue($this->pool);

        $this->assertCount(1, $stored);
        $this->assertSame($value, $stored[0]);
    }

    /**
     * Test that get_connection() returns pooled connection if requested.
     *
     * @covers Lunr\Gravity\DatabaseConnectionPool::get_connection
     */
    public function testGetReadonlyConnectionReturnsPooledConnection(): void
    {
        $method = $this->poolReflection->getMethod('get_connection');
        $method->setAccessible(TRUE);

        $property = $this->poolReflection->getProperty('roPool');
        $property->setAccessible(TRUE);

        $this->assertEmpty($property->getValue($this->pool));

        $method->invokeArgs($this->pool, [ FALSE, TRUE ]);

        $stored = $property->getValue($this->pool);

        $this->assertCount(1, $stored);

        $value = $method->invokeArgs($this->pool, [ FALSE, TRUE ]);

        $stored = $property->getValue($this->pool);

        $this->assertCount(1, $stored);
        $this->assertSame($value, $stored[0]);
    }

    /**
     * Test that get_connection() returns pooled connection if requested.
     *
     * @covers Lunr\Gravity\DatabaseConnectionPool::get_connection
     */
    public function testGetReadWriteConnectionReturnsPooledConnection(): void
    {
        $method = $this->poolReflection->getMethod('get_connection');
        $method->setAccessible(TRUE);

        $property = $this->poolReflection->getProperty('rwPool');
        $property->setAccessible(TRUE);

        $this->assertEmpty($property->getValue($this->pool));

        $method->invokeArgs($this->pool, [ FALSE, FALSE ]);

        $stored = $property->getValue($this->pool);

        $this->assertCount(1, $stored);

        $value = $method->invokeArgs($this->pool, [ FALSE, FALSE ]);

        $stored = $property->getValue($this->pool);

        $this->assertCount(1, $stored);
        $this->assertSame($value, $stored[0]);
    }

}

?>
