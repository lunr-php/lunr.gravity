<?php

/**
 * This file contains the MariaDBDatabaseAccessObjectTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2019 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MariaDB\Tests\Helpers;

use Lunr\Gravity\MariaDB\MariaDBDMLQueryBuilder;
use Lunr\Gravity\MariaDB\MariaDBSimpleDMLQueryBuilder;
use Lunr\Gravity\MySQL\MySQLQueryEscaper;
use Lunr\Gravity\Tests\Helpers\DatabaseAccessObjectBaseTest;
use ReflectionClass;

/**
 * This class contains setup and tear down methods for DAOs using MariaDB access.
 */
abstract class MariaDBDatabaseAccessObjectTest extends DatabaseAccessObjectBaseTest
{

    /**
     * Mock instance of the MariaDBConnection class.
     * @var \Lunr\Gravity\MariaDB\MariaDBConnection
     */
    protected $db;

    /**
     * Mock instance of the Logger class
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * Mock instance of the DMLQueryBuilder class
     * @var \Lunr\Gravity\MariaDB\MariaDBDMLQueryBuilder
     */
    protected $builder;

    /**
     * Real instance of the DMLQueryBuilder class
     * @var \Lunr\Gravity\MariaDB\MariaDBDMLQueryBuilder
     */
    protected $real_builder;

    /**
     * Real instance of the SimpleDMLQueryBuilder class
     * @var \Lunr\Gravity\MariaDB\MariaDBSimpleDMLQueryBuilder
     */
    protected $real_simple_builder;

    /**
     * Mock instance of the QueryEscaper class
     * @var \Lunr\Gravity\MySQL\MySQLQueryEscaper
     */
    protected $escaper;

    /**
     * Real instance of the QueryEscaper class
     * @var \Lunr\Gravity\MySQL\MySQLQueryEscaper
     */
    protected $real_escaper;

    /**
     * Mock instance of the QueryResult class
     * @var \Lunr\Gravity\MySQL\MySQLQueryResult
     */
    protected $result;

    /**
     * Testcase Constructor.
     */
    public function setUp(): void
    {
        $mock_escaper = $this->getMockBuilder('Lunr\Gravity\DatabaseStringEscaperInterface')
                             ->getMock();

        $mock_escaper->expects($this->any())
                     ->method('escape_string')
                     ->willReturnArgument(0);

        $this->real_builder = new MariaDBDMLQueryBuilder();
        $this->real_escaper = new MySQLQueryEscaper($mock_escaper);

        $this->real_simple_builder = new MariaDBSimpleDMLQueryBuilder($this->real_builder, $this->real_escaper);

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
        unset($this->real_escaper);
        unset($this->real_builder);
        unset($this->real_simple_builder);

        parent::tearDown();
    }

}

?>
