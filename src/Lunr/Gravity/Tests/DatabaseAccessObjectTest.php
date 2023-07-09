<?php

/**
 * This file contains the DatabaseAccessObjectTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2012 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\Tests;

use Lunr\Gravity\DatabaseAccessObject;
use Lunr\Gravity\DatabaseConnection;
use Lunr\Halo\LunrBaseTest;
use Psr\Log\LoggerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\MockedType;
use ReflectionClass;

/**
 * This class contains the tests for the DatabaseAccessObject class.
 *
 * @covers Lunr\Gravity\DatabaseAccessObject
 */
abstract class DatabaseAccessObjectTest extends LunrBaseTest
{

    /**
     * Mock instance of a DatabaseConnection
     * @var DatabaseConnection&MockObject&MockedType
     */
    protected DatabaseConnection $db;

    /**
     * Mock instance of the Logger class.
     * @var LoggerInterface&MockObject&MockedType
     */
    protected LoggerInterface $logger;

    /**
     * Testcase Constructor.
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->logger = $this->getMockBuilder(LoggerInterface::class)->getMock();

        $this->db = $this->getMockBuilder(DatabaseConnection::class)
                         ->disableOriginalConstructor()
                         ->getMock();

        $this->class = $this->getMockBuilder(DatabaseAccessObject::class)
                            ->setConstructorArgs([ $this->db, $this->logger ])
                            ->getMockForAbstractClass();

        $this->reflection = new ReflectionClass(DatabaseAccessObject::class);
    }

    /**
     * Testcase Destructor.
     */
    public function tearDown(): void
    {
        unset($this->db);
        unset($this->logger);

        parent::tearDown();
    }

}

?>
