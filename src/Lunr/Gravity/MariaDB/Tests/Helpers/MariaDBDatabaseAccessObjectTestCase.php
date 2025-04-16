<?php

/**
 * This file contains the MariaDBDatabaseAccessObjectTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2019 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MariaDB\Tests\Helpers;

use Lunr\Gravity\MariaDB\MariaDBConnection;
use Lunr\Gravity\MariaDB\MariaDBDMLQueryBuilder;
use Lunr\Gravity\MariaDB\MariaDBSimpleDMLQueryBuilder;
use Lunr\Gravity\MySQL\MySQLQueryEscaper;
use Lunr\Gravity\MySQL\MySQLQueryResult;
use Lunr\Gravity\Tests\Helpers\DatabaseAccessObjectBaseTestCase;
use Psr\Log\LoggerInterface;

/**
 * This class contains setup and tear down methods for DAOs using MariaDB access.
 */
abstract class MariaDBDatabaseAccessObjectTestCase extends DatabaseAccessObjectBaseTestCase
{

    /**
     * Mock instance of the MariaDBConnection class.
     * @var MariaDBConnection
     */
    protected $db;

    /**
     * Mock instance of the Logger class
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Mock instance of the DMLQueryBuilder class
     * @var MariaDBDMLQueryBuilder
     */
    protected $builder;

    /**
     * Real instance of the DMLQueryBuilder class
     * @var MariaDBDMLQueryBuilder
     */
    protected $realBuilder;

    /**
     * Real instance of the SimpleDMLQueryBuilder class
     * @var MariaDBSimpleDMLQueryBuilder
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

        $this->realBuilder = new MariaDBDMLQueryBuilder();
        $this->realEscaper = new MySQLQueryEscaper($mockEscaper);

        $this->realSimpleBuilder = new MariaDBSimpleDMLQueryBuilder($this->realBuilder, $this->realEscaper);

        $this->db = $this->getMockBuilder('Lunr\Gravity\MariaDB\MariaDBConnection')
                         ->disableOriginalConstructor()
                         ->getMock();

        $this->builder = $this->getMockBuilder('Lunr\Gravity\MariaDB\MariaDBDMLQueryBuilder')
                              ->disableOriginalConstructor()
                              ->getMock();

        $this->escaper = $this->getMockBuilder('Lunr\Gravity\MySQL\MySQLQueryEscaper')
                              ->disableOriginalConstructor()
                              ->getMock();

        $this->result = $this->getMockBuilder('Lunr\Gravity\MySQL\MySQLQueryResult')
                             ->disableOriginalConstructor()
                             ->getMock();

        $this->logger = $this->getMockBuilder('Psr\Log\LoggerInterface')->getMock();

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
