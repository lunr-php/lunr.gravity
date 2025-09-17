<?php

/**
 * This file contains the MariaDBConnectionTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2018 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MariaDB\Tests;

use Lunr\Gravity\MariaDB\MariaDBConnection;
use Lunr\Halo\LunrBaseTestCase;

/**
 * This class contains common constructors/destructors for testing the MariaDBConnection class.
 *
 * @covers Lunr\Gravity\MariaDB\MariaDBConnection
 */
abstract class MariaDBConnectionTestCase extends LunrBaseTestCase
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
     * @var MariaDBConnection
     */
    protected MariaDBConnection $class;

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
                            ->willReturnMap($map);

        $map = [
            [ 'rw_host', 'rw_host' ],
            [ 'username', 'username' ],
            [ 'password', 'password' ],
            [ 'database', 'database' ],
            [ 'driver', 'mariadb' ],
        ];

        $this->subConfiguration->expects($this->any())
                               ->method('offsetGet')
                               ->willReturnMap($map);

        $this->logger = $this->getMockBuilder('Psr\Log\LoggerInterface')->getMock();

        $this->mysqli = $this->getMockBuilder('\mysqli')->getMock();

        $this->class = new MariaDBConnection($this->configuration, $this->logger, $this->mysqli);

        parent::baseSetUp($this->class);
    }

    /**
     * TestCase Destructor.
     */
    public function tearDown(): void
    {
        unset($this->configuration);
        unset($this->logger);
        unset($this->class);

        parent::tearDown();
    }

}

?>
