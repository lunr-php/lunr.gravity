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

/**
 * This class contains common constructors/destructors and data providers
 * for testing the MySQLConnection class.
 *
 * @covers Lunr\Gravity\MySQL\MySQLConnection
 */
abstract class MySQLConnectionTestCase extends LunrBaseTestCase
{

    /**
     * Mock instance of the sub Configuration class.
     * @var Configuration
     */
    protected $subConfiguration;

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
        $this->subConfiguration = $this->getMockBuilder('Lunr\Core\Configuration')->getMock();

        $this->configuration = $this->getMockBuilder('Lunr\Core\Configuration')->getMock();

        $map = [
            [ 'db', $this->subConfiguration ],
        ];

        $this->configuration->expects($this->any())
                            ->method('offsetGet')
                            ->will($this->returnValueMap($map));

        $this->logger = $this->getMockBuilder('Psr\Log\LoggerInterface')->getMock();

        $this->mysqli = $this->getMockBuilder('\mysqli')->getMock();

        $this->class = new MySQLConnection($this->configuration, $this->logger, $this->mysqli);

        parent::baseSetUp($this->class);
    }

    /**
     * TestCase Constructor.
     */
    public function setUp(): void
    {
        $this->subConfiguration = $this->getMockBuilder('Lunr\Core\Configuration')->getMock();

        $this->configuration = $this->getMockBuilder('Lunr\Core\Configuration')->getMock();

        $map = [
            [ 'db', $this->subConfiguration ],
        ];

        $this->configuration->expects($this->any())
                            ->method('offsetGet')
                            ->will($this->returnValueMap($map));

        $map = [
            [ 'rw_host', 'rw_host' ],
            [ 'username', 'username' ],
            [ 'password', 'password' ],
            [ 'database', 'database' ],
            [ 'driver', 'mysql' ],
        ];

        $this->subConfiguration->expects($this->any())
                               ->method('offsetGet')
                               ->will($this->returnValueMap($map));

        $this->logger = $this->getMockBuilder('Psr\Log\LoggerInterface')->getMock();

        $this->mysqli = $this->getMockBuilder('\mysqli')->getMock();

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
