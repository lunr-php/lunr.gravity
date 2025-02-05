<?php

/**
 * This file contains the SQLite3AccessObjectTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2023 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\SQLite3\Tests;

use Lunr\Gravity\SQLite3\SQLite3AccessObject;
use Lunr\Gravity\SQLite3\SQLite3Connection;
use Lunr\Gravity\SQLite3\SQLite3QueryEscaper;
use Lunr\Halo\LunrBaseTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;
use Psr\Log\LoggerInterface;

/**
 * This class contains the tests for the SQLite3AccessObject class.
 *
 * @covers Lunr\Gravity\SQLite3AccessObject
 */
abstract class SQLite3AccessObjectTest extends LunrBaseTestCase
{

    /**
     * Mock instance of a SQLite3Connection
     * @var SQLite3Connection&MockObject&Stub
     */
    protected SQLite3Connection $db;

    /**
     * Mock instance of the Logger class.
     * @var LoggerInterface&MockObject&Stub
     */
    protected LoggerInterface $logger;

    /**
     * Instance of the tested class.
     * @var SQLite3AccessObject&MockObject&Stub
     */
    protected SQLite3AccessObject&MockObject&Stub $class;

    /**
     * Testcase Constructor.
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->logger = $this->getMockBuilder(LoggerInterface::class)->getMock();

        $this->db = $this->getMockBuilder(SQLite3Connection::class)
                         ->disableOriginalConstructor()
                         ->getMock();

        $escaper = $this->getMockBuilder(SQLite3QueryEscaper::class)
                        ->disableOriginalConstructor()
                        ->getMock();

        $this->db->expects($this->once())
                 ->method('get_query_escaper_object')
                 ->willReturn($escaper);

        $this->class = $this->getMockBuilder(SQLite3AccessObject::class)
                            ->setConstructorArgs([ $this->db, $this->logger ])
                            ->getMockForAbstractClass();

        parent::baseSetUp($this->class);
    }

    /**
     * Testcase Destructor.
     */
    public function tearDown(): void
    {
        unset($this->db);
        unset($this->logger);
        unset($this->class);

        parent::tearDown();
    }

}

?>
