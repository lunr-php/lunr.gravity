<?php

/**
 * This file contains the AbstractMySQLDatabaseAccessObjectTest class.
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
use Lunr\Gravity\Tests\Helpers\DatabaseAccessObjectBaseTest;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;

/**
 * This class contains setup and tear down methods for DAOs using MySQL access.
 */
abstract class AbstractMySQLDatabaseAccessObjectTestCase extends DatabaseAccessObjectBaseTest
{

    /**
     * Mock instance of the MySQLConnection class.
     * @var MySQLConnection|MockObject
     */
    protected $db;

    /**
     * Mock instance of the Logger class
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Mock instance of the DMLQueryBuilder class
     * @var MySQLDMLQueryBuilder|MockObject
     */
    protected $builder;

    /**
     * Real instance of the DMLQueryBuilder class
     * @var MySQLDMLQueryBuilder
     */
    protected $real_builder;

    /**
     * Real instance of the SimpleDMLQueryBuilder class
     * @var MySQLSimpleDMLQueryBuilder
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

        $this->real_builder = new MySQLDMLQueryBuilder();
        $this->real_escaper = new MySQLQueryEscaper($mock_escaper);

        $this->real_simple_builder = new MySQLSimpleDMLQueryBuilder($this->real_builder, $this->real_escaper);

        $this->db = $this->getMockBuilder('Lunr\Gravity\MySQL\MySQLConnection')
                         ->disableOriginalConstructor()
                         ->getMock();

        $this->builder = $this->getMockBuilder('Lunr\Gravity\MySQL\MySQLDMLQueryBuilder')
                              ->disableOriginalConstructor()
                              ->getMock();

        $this->escaper = $this->getMockBuilder('Lunr\Gravity\MySQL\MySQLQueryEscaper')
                              ->disableOriginalConstructor()
                              ->getMock();

        $this->result = $this->getMockBuilder('Lunr\Gravity\MySQL\MySQLQueryResult')
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
