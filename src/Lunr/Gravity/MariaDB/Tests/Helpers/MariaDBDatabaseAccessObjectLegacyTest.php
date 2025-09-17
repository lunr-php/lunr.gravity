<?php

/**
 * This file contains the MariaDBDatabaseAccessObjectLegacyTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2019 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MariaDB\Tests\Helpers;

use Lunr\Gravity\MariaDB\MariaDBConnection;
use Lunr\Gravity\MariaDB\MariaDBDMLQueryBuilder;
use Lunr\Gravity\MySQL\MySQLQueryEscaper;
use Lunr\Gravity\MySQL\MySQLQueryResult;
use Lunr\Halo\LegacyBaseTest;
use MySQLi;
use Psr\Log\LoggerInterface;
use ReflectionClass;

/**
 * This class contains setup and tear down methods for DAOs using MariaDB access.
 *
 * @deprecated User `MariaDBDatabaseAccessObjectTest` instead
 */
abstract class MariaDBDatabaseAccessObjectLegacyTest extends LegacyBaseTest
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
     * Mock instance of the QueryEscaper class
     * @var MySQLQueryEscaper
     */
    protected $escaper;

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

        $this->db = $this->getMockBuilder('Lunr\Gravity\MariaDB\MariaDBConnection')
                         ->setConstructorArgs([ $config, $this->logger, $mysqli ])
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

        $this->db->expects($this->once())
                 ->method('get_query_escaper_object')
                 ->willReturn($this->escaper);

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
