<?php

/**
 * This file contains the MySQLDatabaseAccessObjectLegacyTest class.
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
use Lunr\Halo\LegacyBaseTest;
use Psr\Log\LoggerInterface;
use ReflectionClass;

/**
 * This class contains setup and tear down methods for DAOs using MySQL access.
 *
 * @deprecated Use `MySQLDatabaseAccessObjectTest` instead
 */
abstract class MySQLDatabaseAccessObjectLegacyTest extends LegacyBaseTest
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
    protected $real_builder;

    /**
     * Real instance of the SimpleDMLQueryBuilder class
     * @var MySQLSimpleDMLQueryBuilder
     */
    protected $real_simple_builder;

    /**
     * Mock instance of the QueryEscaper class
     * @var MySQLQueryEscaper
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

        $this->db->expects($this->once())
                 ->method('get_query_escaper_object')
                 ->will($this->returnValue($this->escaper));

        // Assumption: All DAO's end in DAO.
        $name = str_replace('\\Tests\\', '\\', substr(static::class, 0, strrpos(static::class, 'DAO') + 3));

        $this->class = new $name($this->db, $this->logger);

        $this->reflection = new ReflectionClass($name);
    }

    /**
     * Testcase Destructor.
     */
    public function tearDown(): void
    {
        unset($this->class);
        unset($this->reflection);
        unset($this->db);
        unset($this->logger);
        unset($this->builder);
        unset($this->escaper);
        unset($this->result);
        unset($this->real_escaper);
        unset($this->real_builder);
        unset($this->real_simple_builder);
    }

    /**
     * Reports an error if the value of $actualSql does not match the value in $expectedFile.
     *
     * @param string $expectedFile File containing the (optionally pretty-printed) expected SQL query
     * @param string $actualSql    The actual SQL query string
     *
     * @return void
     */
    public function assertSqlStringEqualsSqlFile($expectedFile, $actualSql): void
    {
        $formatted = file_get_contents($expectedFile);
        $formatted = preg_replace('/\s--.*/', '', $formatted);
        $formatted = trim(preg_replace('/\s+/', ' ', $formatted));
        $formatted = str_replace('( ', '(', $formatted);
        $formatted = str_replace(' )', ')', $formatted);

        $this->assertEquals($formatted, $actualSql);
    }

}

?>
