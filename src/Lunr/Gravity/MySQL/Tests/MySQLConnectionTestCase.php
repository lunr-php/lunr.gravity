<?php

/**
 * This file contains the MySQLConnectionTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2012 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MySQL\Tests;

use Lunr\Gravity\MySQL\MySQLConnection;
use Lunr\Halo\LunrBaseTestCase;
use Lunr\Ticks\EventLogging\EventInterface;
use Lunr\Ticks\EventLogging\EventLoggerInterface;
use Lunr\Ticks\TracingControllerInterface;
use Lunr\Ticks\TracingInfoInterface;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * This class contains common constructors/destructors and data providers
 * for testing the MySQLConnection class.
 *
 * @covers Lunr\Gravity\MySQL\MySQLConnection
 */
abstract class MySQLConnectionTestCase extends LunrBaseTestCase
{

    use MockeryPHPUnitIntegration;

    /**
     * Mock instance of the Configuration class.
     * @var Configuration
     */
    protected $configuration;

    /**
     * Mock instance of the Logger class.
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Mock instance of the mysqli class.
     * @var mysqli
     */
    protected $mysqli;

    /**
     * Mock Instance of an event logger.
     * @var EventLoggerInterface&MockObject
     */
    protected EventLoggerInterface&MockObject $eventLogger;

    /**
     * Mock instance of a Controller
     * @var TracingControllerInterface&TracingInfoInterface&MockInterface
     */
    protected TracingControllerInterface&TracingInfoInterface&MockInterface $controller;

    /**
     * Mock Instance of an analytics event.
     * @var EventInterface&MockObject
     */
    protected EventInterface&MockObject $event;

    /**
     * Instance of the tested class.
     * @var MySQLConnection
     */
    protected MySQLConnection $class;

    /**
     * TestCase Constructor.
     *
     * @return void
     */
    public function emptySetUp(): void
    {
        $this->configuration = $this->getMockBuilder('Lunr\Core\Configuration')->getMock();

        $this->logger = $this->getMockBuilder('Psr\Log\LoggerInterface')->getMock();

        $this->mysqli = $this->getMockBuilder('\mysqli')->getMock();

        $this->eventLogger = $this->getMockBuilder(EventLoggerInterface::class)
                                  ->getMock();

        $this->event = $this->getMockBuilder(EventInterface::class)
                            ->getMock();

        $this->controller = Mockery::mock(
                                TracingControllerInterface::class,
                                TracingInfoInterface::class,
                            );

        $this->class = new MySQLConnection($this->configuration, $this->logger, $this->mysqli);

        parent::baseSetUp($this->class);
    }

    /**
     * TestCase Constructor.
     */
    public function setUp(): void
    {
        $this->configuration = $this->getMockBuilder('Lunr\Core\Configuration')->getMock();

        $map = [
            [ 'rwHost', 'rwHost' ],
            [ 'username', 'username' ],
            [ 'password', 'password' ],
            [ 'database', 'database' ],
            [ 'driver', 'mysql' ],
        ];

        $this->configuration->expects($this->any())
                            ->method('offsetGet')
                            ->will($this->returnValueMap($map));

        $this->logger = $this->getMockBuilder('Psr\Log\LoggerInterface')->getMock();

        $this->mysqli = $this->getMockBuilder('\mysqli')->getMock();

        $this->eventLogger = $this->getMockBuilder(EventLoggerInterface::class)
                                  ->getMock();

        $this->event = $this->getMockBuilder(EventInterface::class)
                            ->getMock();

        $this->controller = Mockery::mock(
                                TracingControllerInterface::class,
                                TracingInfoInterface::class,
                            );

        $this->class = new MySQLConnection($this->configuration, $this->logger, $this->mysqli);

        parent::baseSetUp($this->class);
    }

    /**
     * TestCase Destructor.
     */
    public function tearDown(): void
    {
        unset($this->class);
        unset($this->configuration);
        unset($this->logger);
        unset($this->eventLogger);
        unset($this->event);
        unset($this->controller);

        parent::tearDown();
    }

    /**
     * Unit Test Data Provider for strings to escape.
     *
     * @return array $strings Array of strings and their expected escaped value
     */
    public function escapeStringProvider(): array
    {
        $strings   = [];
        $strings[] = [ "'--", "\'--", "\'--" ];
        $strings[] = [ "\'--", "\\\'--", "\\\'--" ];
        $strings[] = [ '70%', '70%', '70%' ];
        $strings[] = [ 'test_name', 'test_name', 'test_name' ];

        return $strings;
    }

}

?>
