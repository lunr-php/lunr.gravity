<?php

/**
 * This file contains the AbstractMariaDBDatabaseAccessObjectTest class.
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
use Lunr\Gravity\Tests\Helpers\DatabaseAccessObjectBaseTest;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;

/**
 * This class contains setup and tear down methods for DAOs using MariaDB access.
 */
abstract class AbstractMariaDBDatabaseAccessObjectTestCase extends DatabaseAccessObjectBaseTest
{

    /**
     * Mock instance of the MariaDBConnection class.
     * @var MariaDBConnection|MockObject
     */
    protected $db;

    /**
     * Mock instance of the Logger class
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Mock instance of the DMLQueryBuilder class
     * @var MariaDBDMLQueryBuilder|MockObject
     */
    protected $builder;

    /**
     * Real instance of the DMLQueryBuilder class
     * @var MariaDBDMLQueryBuilder
     */
    protected $real_builder;

    /**
     * Real instance of the SimpleDMLQueryBuilder class
     * @var MariaDBSimpleDMLQueryBuilder
     */
    protected $real_simple_builder;

    /**
     * Mock instance of the QueryEscaper class
     * @var MySQLQueryEscaper|MockObject
     */
    protected $escaper;

    /**
     * Real instance of the QueryEscaper class
     * @var MySQLQueryEscaper
     */
    protected $real_escaper;

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

        $this->escaper = $this->getMockBuilder('Lunr\Gravity\MariaDB\MySQLQueryEscaper')
                              ->disableOriginalConstructor()
                              ->getMock();

        $this->result = $this->getMockBuilder('Lunr\Gravity\MariaDB\MySQLQueryResult')
                             ->disableOriginalConstructor()
                             ->getMock();

        $this->logger = $this->getMockBuilder('Psr\Log\LoggerInterface')->getMock();

        $this->db->expects($this->exactly(1))
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
