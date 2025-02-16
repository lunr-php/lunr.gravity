<?php

/**
 * This file contains the DatabaseConnectionPoolEmptyTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2012 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\Tests;

/**
 * This class contains tests for the DatabaseConnectionPool class.
 * Specifically for the case when there is no database configuration present.
 *
 * @covers Lunr\Gravity\DatabaseConnectionPool
 */
class DatabaseConnectionPoolEmptyTest extends DatabaseConnectionPoolTestCase
{

    /**
     * TestCase Constructor.
     */
    public function setUp(): void
    {
        $this->emptySetup();
    }

    /**
     * Test that get_connection returns NULL.
     *
     * @covers Lunr\Gravity\DatabaseConnectionPool::get_connection
     */
    public function testGetNewAndReadonlyConnectionReturnsNull(): void
    {
        $method = $this->poolReflection->getMethod('get_connection');
        $method->setAccessible(TRUE);

        $this->assertNull($method->invokeArgs($this->pool, [ TRUE, TRUE ]));
    }

    /**
     * Test that get_connection returns NULL.
     *
     * @covers Lunr\Gravity\DatabaseConnectionPool::get_connection
     */
    public function testGetNewAndReadWriteConnectionReturnsNull(): void
    {
        $method = $this->poolReflection->getMethod('get_connection');
        $method->setAccessible(TRUE);

        $this->assertNull($method->invokeArgs($this->pool, [ TRUE, FALSE ]));
    }

    /**
     * Test that get_connection returns NULL.
     *
     * @covers Lunr\Gravity\DatabaseConnectionPool::get_connection
     */
    public function testGetReadonlyConnectionReturnsNull(): void
    {
        $method = $this->poolReflection->getMethod('get_connection');
        $method->setAccessible(TRUE);

        $this->assertNull($method->invokeArgs($this->pool, [ FALSE, TRUE ]));
    }

    /**
     * Test that get_connection returns NULL.
     *
     * @covers Lunr\Gravity\DatabaseConnectionPool::get_connection
     */
    public function testGetReadwriteConnectionReturnsNull(): void
    {
        $method = $this->poolReflection->getMethod('get_connection');
        $method->setAccessible(TRUE);

        $this->assertNull($method->invokeArgs($this->pool, [ FALSE, FALSE ]));
    }

    /**
     * Test that get_connection() with empty db config does not alter roPool.
     *
     * @covers Lunr\Gravity\DatabaseConnectionPool::get_connection
     */
    public function testGetNewAndReadonlyConnectionDoesNotAlterPool(): void
    {
        $method = $this->poolReflection->getMethod('get_connection');
        $method->setAccessible(TRUE);

        $property = $this->poolReflection->getProperty('roPool');
        $property->setAccessible(TRUE);

        $old = $property->getValue($this->pool);

        $method->invokeArgs($this->pool, [ TRUE, TRUE ]);

        $new = $property->getValue($this->pool);

        $this->assertEquals($old, $new);
    }

    /**
     * Test that get_connection() with empty db config does not alter rwPool.
     *
     * @covers Lunr\Gravity\DatabaseConnectionPool::get_connection
     */
    public function testGetNewAndReadWriteConnectionDoesNotAlterPool(): void
    {
        $method = $this->poolReflection->getMethod('get_connection');
        $method->setAccessible(TRUE);

        $property = $this->poolReflection->getProperty('rwPool');
        $property->setAccessible(TRUE);

        $old = $property->getValue($this->pool);

        $method->invokeArgs($this->pool, [ TRUE, FALSE ]);

        $new = $property->getValue($this->pool);

        $this->assertEquals($old, $new);
    }

    /**
     * Test that get_connection() with empty db config does not alter roPool.
     *
     * @covers Lunr\Gravity\DatabaseConnectionPool::get_connection
     */
    public function testGetReadonlyConnectionDoesNotAlterPool(): void
    {
        $method = $this->poolReflection->getMethod('get_connection');
        $method->setAccessible(TRUE);

        $property = $this->poolReflection->getProperty('roPool');
        $property->setAccessible(TRUE);

        $old = $property->getValue($this->pool);

        $method->invokeArgs($this->pool, [ FALSE, TRUE ]);

        $new = $property->getValue($this->pool);

        $this->assertEquals($old, $new);
    }

    /**
     * Test that get_connection() with empty db config does not alter rwPool.
     *
     * @covers Lunr\Gravity\DatabaseConnectionPool::get_connection
     */
    public function testGetReadWriteConnectionDoesNotAlterPool(): void
    {
        $method = $this->poolReflection->getMethod('get_connection');
        $method->setAccessible(TRUE);

        $property = $this->poolReflection->getProperty('rwPool');
        $property->setAccessible(TRUE);

        $old = $property->getValue($this->pool);

        $method->invokeArgs($this->pool, [ FALSE, FALSE ]);

        $new = $property->getValue($this->pool);

        $this->assertEquals($old, $new);
    }

    /**
     * Test that get_new_ro_connection returns NULL.
     *
     * @depends testGetNewAndReadonlyConnectionReturnsNull
     * @covers  Lunr\Gravity\DatabaseConnectionPool::get_new_ro_connection
     */
    public function testGetNewRoConnectionReturnsNull(): void
    {
        $this->assertNull($this->pool->get_new_ro_connection());
    }

    /**
     * Test that get_new_rw_connection returns NULL.
     *
     * @depends testGetNewAndReadWriteConnectionReturnsNull
     * @covers  Lunr\Gravity\DatabaseConnectionPool::get_new_rw_connection
     */
    public function testGetNewRwConnectionReturnsNull(): void
    {
        $this->assertNull($this->pool->get_new_rw_connection());
    }

    /**
     * Test that get_ro_connection returns NULL.
     *
     * @depends testGetReadonlyConnectionReturnsNull
     * @covers  Lunr\Gravity\DatabaseConnectionPool::get_ro_connection
     */
    public function testGetRoConnectionReturnsNull(): void
    {
        $this->assertNull($this->pool->get_ro_connection());
    }

    /**
     * Test that get_rw_connection returns NULL.
     *
     * @depends testGetReadwriteConnectionReturnsNull
     * @covers  Lunr\Gravity\DatabaseConnectionPool::get_rw_connection
     */
    public function testGetRwConnectionReturnsNull(): void
    {
        $this->assertNull($this->pool->get_rw_connection());
    }

    /**
     * Test that get_connection() with empty db config does not alter rwPool.
     *
     * @depends testGetNewAndReadonlyConnectionDoesNotAlterPool
     * @covers  Lunr\Gravity\DatabaseConnectionPool::get_connection
     */
    public function testGetNewRoConnectionDoesNotAlterPool(): void
    {
        $property = $this->poolReflection->getProperty('roPool');
        $property->setAccessible(TRUE);

        $old = $property->getValue($this->pool);

        $this->pool->get_new_ro_connection();

        $new = $property->getValue($this->pool);

        $this->assertEquals($old, $new);
    }

    /**
     * Test that get_connection() with empty db config does not alter rwPool.
     *
     * @depends testGetNewAndReadWriteConnectionDoesNotAlterPool
     * @covers  Lunr\Gravity\DatabaseConnectionPool::get_connection
     */
    public function testGetNewRwConnectionDoesNotAlterPool(): void
    {
        $property = $this->poolReflection->getProperty('roPool');
        $property->setAccessible(TRUE);

        $old = $property->getValue($this->pool);

        $this->pool->get_new_rw_connection();

        $new = $property->getValue($this->pool);

        $this->assertEquals($old, $new);
    }

    /**
     * Test that get_connection() with empty db config does not alter rwPool.
     *
     * @depends testGetReadonlyConnectionDoesNotAlterPool
     * @covers  Lunr\Gravity\DatabaseConnectionPool::get_connection
     */
    public function testGetRoConnectionDoesNotAlterPool(): void
    {
        $property = $this->poolReflection->getProperty('roPool');
        $property->setAccessible(TRUE);

        $old = $property->getValue($this->pool);

        $this->pool->get_ro_connection();

        $new = $property->getValue($this->pool);

        $this->assertEquals($old, $new);
    }

    /**
     * Test that get_connection() with empty db config does not alter rwPool.
     *
     * @depends testGetReadWriteConnectionDoesNotAlterPool
     * @covers  Lunr\Gravity\DatabaseConnectionPool::get_connection
     */
    public function testGetRwConnectionDoesNotAlterPool(): void
    {
        $property = $this->poolReflection->getProperty('roPool');
        $property->setAccessible(TRUE);

        $old = $property->getValue($this->pool);

        $this->pool->get_rw_connection();

        $new = $property->getValue($this->pool);

        $this->assertEquals($old, $new);
    }

}

?>
