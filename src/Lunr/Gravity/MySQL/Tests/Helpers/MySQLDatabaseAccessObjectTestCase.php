<?php

/**
 * This file contains the MySQLDatabaseAccessObjectTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2014 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MySQL\Tests\Helpers;

use Lunr\Gravity\MySQL\MySQLConnection;
use Lunr\Gravity\MySQL\MySQLDMLQueryBuilder;
use Lunr\Gravity\MySQL\MySQLQueryEscaper;
use Lunr\Gravity\MySQL\MySQLQueryResult;
use Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder;
use Lunr\Gravity\Tests\Helpers\DatabaseAccessObjectBaseTestCase;
use MySQLi;
use Psr\Log\LoggerInterface;

/**
 * This class contains setup and tear down methods for DAOs using MySQL access.
 */
abstract class MySQLDatabaseAccessObjectTestCase extends DatabaseAccessObjectBaseTestCase
{

    /**
     * Mock instance of the MySQLConnection class.
     * @var MySQLConnection
     */
    protected $db;

    /**
     * Mock instance of the Logger class
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Mock instance of the DMLQueryBuilder class
     * @var MySQLDMLQueryBuilder
     */
    protected $builder;

    /**
     * Real instance of the DMLQueryBuilder class
     * @var MySQLDMLQueryBuilder
     */
    protected $realBuilder;

    /**
     * Real instance of the SimpleDMLQueryBuilder class
     * @var MySQLSimpleDMLQueryBuilder
     */
    protected $realSimpleBuilder;

    /**
     * Mock instance of the QueryEscaper class
     * @var MySQLQueryEscaper
     */
    protected $escaper;

    /**
     * Real instance of the QueryEscaper class
     * @var MySQLQueryEscaper
     */
    protected $realEscaper;

    /**
     * Mock instance of the QueryResult class
     * @var MySQLQueryResult
     */
    protected $result;

    /**
     * Testcase Constructor.
     */
    public function setUp(): void
    {
        $mockEscaper = $this->getMockBuilder('Lunr\Gravity\DatabaseStringEscaperInterface')
                            ->getMock();

        $mockEscaper->expects($this->any())
                    ->method('escape_string')
                    ->willReturnArgument(0);

        $this->realBuilder = new MySQLDMLQueryBuilder();
        $this->realEscaper = new MySQLQueryEscaper($mockEscaper);

        $this->realSimpleBuilder = new MySQLSimpleDMLQueryBuilder($this->realBuilder, $this->realEscaper);

        $this->logger = $this->getMockBuilder('Psr\Log\LoggerInterface')->getMock();

        $mysqli = $this->getMockBuilder(MySQLi::class)
                       ->disableOriginalConstructor()
                       ->getMock();

        $config = [
            'rwHost'   => 'localhost',
            'username' => 'user',
            'password' => 'pass',
            'database' => 'db',
            'driver'   => 'mysql',
        ];

        $this->db = $this->getMockBuilder('Lunr\Gravity\MySQL\MySQLConnection')
                         ->setConstructorArgs([ $config, $this->logger, $mysqli ])
                         ->getMock();

        $this->escaper = $this->getMockBuilder('Lunr\Gravity\MySQL\MySQLQueryEscaper')
                              ->disableOriginalConstructor()
                              ->getMock();

        $this->result = $this->getMockBuilder('Lunr\Gravity\MySQL\MySQLQueryResult')
                             ->disableOriginalConstructor()
                             ->getMock();

        $this->db->expects($this->once())
                 ->method('get_query_escaper_object')
                 ->will($this->returnValue($this->escaper));
    }

    /**
     * Testcase Destructor.
     */
    public function tearDown(): void
    {
        unset($this->db);
        unset($this->logger);
        unset($this->builder);
        unset($this->escaper);
        unset($this->result);
        unset($this->realEscaper);
        unset($this->realBuilder);
        unset($this->realSimpleBuilder);

        parent::tearDown();
    }

}

?>
