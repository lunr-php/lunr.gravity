<?php

/**
 * This file contains the DatabaseConnectionPoolBaseTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2012 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\Tests;

/**
 * This class contains basic tests for the DatabaseConnectionPool class.
 *
 * @covers Lunr\Gravity\DatabaseConnectionPool
 */
class DatabaseConnectionPoolBaseTest extends DatabaseConnectionPoolTestCase
{

    /**
     * TestCase Constructor.
     */
    public function setUp(): void
    {
        $this->emptySetup();
    }

    /**
     * Test that the Configuration class was passed correctly.
     */
    public function testConfigurationPassedByReference(): void
    {
        $property = $this->pool_reflection->getProperty('configuration');
        $property->setAccessible(TRUE);

        $value = $property->getValue($this->pool);

        $this->assertInstanceOf('Lunr\Core\Configuration', $value);
        $this->assertSame($this->configuration, $value);
    }

    /**
     * Test that the Logger class was passed correctly.
     */
    public function testLoggerPassedByReference(): void
    {
        $property = $this->pool_reflection->getProperty('logger');
        $property->setAccessible(TRUE);

        $value = $property->getValue($this->pool);

        $this->assertInstanceOf('Psr\Log\LoggerInterface', $value);
        $this->assertSame($this->logger, $value);
    }

    /**
     * Test that the ro_pool was setup correctly.
     */
    public function testReadonlyPoolSetupCorrectly(): void
    {
        $property = $this->pool_reflection->getProperty('ro_pool');
        $property->setAccessible(TRUE);

        $value = $property->getValue($this->pool);

        $this->assertIsArray($value);
        $this->assertEmpty($value);
    }

    /**
     * Test that the rw_pool was setup correctly.
     */
    public function testReadWritePoolSetupCorrectly(): void
    {
        $property = $this->pool_reflection->getProperty('rw_pool');
        $property->setAccessible(TRUE);

        $value = $property->getValue($this->pool);

        $this->assertIsArray($value);
        $this->assertEmpty($value);
    }

}

?>
