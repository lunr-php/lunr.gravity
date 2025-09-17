<?php

/**
 * This file contains the SQLite3ConnectionTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2013 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\SQLite3\Tests;

use Lunr\Gravity\SQLite3\SQLite3Connection;
use Lunr\Halo\LunrBaseTestCase;

/**
 * This class contains common constructors/destructors and data providers
 * for testing the SQLite3Connection class.
 *
 * @covers Lunr\Gravity\SQLite3\SQLite3Connection
 */
abstract class SQLite3ConnectionTestCase extends LunrBaseTestCase
{

    /**
     * Mock instance of the Logger class.
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Mock instance of the SQLite3 class.
     * @var SQLite3
     */
    protected $sqlite3;

    /**
     * Mock instance of the SQLite3Result class.
     * @var SQLite3Result
     */
    protected $sqlite3Result;

    /**
     * Mock instance of the Configuration class.
     * @var Configuration
     */
    protected $configuration;

    /**
     * Mock instance of the Configuration class.
     * @var Configuration
     */
    protected $subConfiguration;

    /**
     * Instance of the tested class.
     * @var SQLite3Connection
     */
    protected SQLite3Connection $class;

    /**
     * TestCase Constructor.
     *
     * @return void
     */
    public function emptySetUp(): void
    {
        if (extension_loaded('sqlite3') === FALSE)
        {
            $this->markTestSkipped('Extension sqlite3 is required.');
        }

        $this->configuration = $this->getMockBuilder('Lunr\Core\Configuration')->getMock();

        $this->logger = $this->getMockBuilder('Psr\Log\LoggerInterface')->getMock();

        $this->sqlite3 = $this->getMockBuilder('Lunr\Gravity\SQLite3\LunrSQLite3')->getMock();

        $this->sqlite3->expects($this->once())
                      ->method('enableExceptions')
                      ->with(FALSE);

        $this->sqlite3Result = $this->getMockBuilder('SQLite3Result')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->class = new SQLite3Connection($this->configuration, $this->logger, $this->sqlite3);

        parent::baseSetUp($this->class);
    }

    /**
     * TestCase Constructor.
     */
    public function setUp(): void
    {
        if (extension_loaded('sqlite3') === FALSE)
        {
            $this->markTestSkipped('Extension sqlite3 is required.');
        }

        $this->configuration = $this->getMockBuilder('Lunr\Core\Configuration')->getMock();

        $this->configuration->expects($this->any())
                            ->method('offsetExists')
                            ->willReturn(TRUE);

        $map = [
            [ 'file', '/tmp/test.db' ],
            [ 'driver', 'sqlite3' ],
        ];

        $this->configuration->expects($this->atLeast(1))
                            ->method('offsetGet')
                               ->willReturnMap($map);

        $this->logger = $this->getMockBuilder('Psr\Log\LoggerInterface')->getMock();

        $this->sqlite3 = $this->getMockBuilder('Lunr\Gravity\SQLite3\LunrSQLite3')->getMock();

        $this->sqlite3Result = $this->getMockBuilder('SQLite3Result')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->class = new SQLite3Connection($this->configuration, $this->logger, $this->sqlite3);

        parent::baseSetUp($this->class);
    }

    /**
     * TestCase Destructor.
     */
    public function tearDown(): void
    {
        unset($this->logger);
        unset($this->configuration);
        unset($this->sqlite3);
        unset($this->sqlite3Result);
        unset($this->class);

        parent::tearDown();
    }

    /**
     * Unit Test Data Provider for strings to escape.
     *
     * @return array $strings Array of strings and their expected escaped value
     */
    public static function escapeStringProvider(): array
    {
        $strings   = [];
        $strings[] = [ 'Start', 'Start', 'Start' ];

        return $strings;
    }

}

?>
