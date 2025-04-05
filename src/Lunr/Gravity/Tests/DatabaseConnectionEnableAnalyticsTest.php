<?php

/**
 * This file contains the DatabaseConnectionEnableAnalyticsTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2025 Framna Netherlands B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\Tests;

use Lunr\Ticks\AnalyticsDetailLevel;

/**
 * This class contains tests for the DatabaseConnection class.
 *
 * @covers Lunr\Gravity\DatabaseConnection
 */
class DatabaseConnectionEnableAnalyticsTest extends DatabaseConnectionTestCase
{

    /**
     * Test enableAnalytics() with the default analytics detail level.
     *
     * @covers Lunr\Gravity\DatabaseConnection::enableAnalytics
     */
    public function testEnableAnalyticsWithDefaultAnalyticsDetailLevel(): void
    {
        $this->class->enableAnalytics($this->eventLogger, $this->controller);

        $this->assertPropertySame('eventLogger', $this->eventLogger);
        $this->assertPropertySame('tracingController', $this->controller);

        $this->assertPropertyEquals('analyticsDetailLevel', AnalyticsDetailLevel::Info);
    }

    /**
     * Test enableAnalytics() with a custom analytics detail level.
     *
     * @covers Lunr\Gravity\DatabaseConnection::enableAnalytics
     */
    public function testEnableAnalyticsWithCustomAnalyticsDetailLevel(): void
    {
        $this->class->enableAnalytics($this->eventLogger, $this->controller, AnalyticsDetailLevel::Full);

        $this->assertPropertySame('eventLogger', $this->eventLogger);
        $this->assertPropertySame('tracingController', $this->controller);

        $this->assertPropertyEquals('analyticsDetailLevel', AnalyticsDetailLevel::Full);
    }

}

?>
