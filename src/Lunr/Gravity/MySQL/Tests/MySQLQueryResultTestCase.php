<?php

/**
 * This file contains the MySQLQueryResultTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2012 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MySQL\Tests;

use Lunr\Gravity\MySQL\MySQLQueryResult;
use Lunr\Halo\LunrBaseTestCase;
use mysqli;

/**
 * This class contains common constructors/destructors and data providers
 * for testing the MySQLQueryResult class.
 *
 * @covers Lunr\Gravity\MySQL\MySQLQueryResult
 */
abstract class MySQLQueryResultTestCase extends LunrBaseTestCase
{

    /**
     * Query result
     * @var mixed
     */
    protected $query_result;

    /**
     * Instance of the mysqli class.
     * @var mysqli
     */
    protected $mysqli;

    /**
     * The executed query.
     * @var String
     */
    protected $query;

    /**
     * Instance of the tested class.
     * @var MySQLQueryResult
     */
    protected MySQLQueryResult $class;

    /**
     * TestCase Constructor passing a MySQLi_result object.
     *
     * @return void
     */
    public function resultSetSetup(): void
    {
        $this->mysqli = new MockMySQLiSuccessfulConnection($this->getMockBuilder('\mysqli')->getMock());

        $this->query_result = new MockMySQLiResult($this->getMockBuilder('mysqli_result')
                                                        ->disableOriginalConstructor()
                                                        ->getMock());

        $this->query = 'SELECT * FROM table';

        $this->class = new MySQLQueryResult($this->query, $this->query_result, $this->mysqli);

        parent::baseSetUp($this->class);
    }

    /**
     * TestCase Constructor passing FALSE as query result.
     *
     * @return void
     */
    public function failedSetup(): void
    {
        $this->query_result = FALSE;

        $this->mysqli = new MockMySQLiFailedConnection($this->getMockBuilder('\mysqli')->getMock());

        $this->query = 'SELECT * FROM table';

        $this->class = new MySQLQueryResult($this->query, $this->query_result, $this->mysqli);

        parent::baseSetUp($this->class);
    }

    /**
     * TestCase Constructor passing TRUE as query result.
     *
     * @return void
     */
    public function successfulSetup(): void
    {
        $this->query_result = TRUE;

        $this->mysqli = new MockMySQLiSuccessfulConnection($this->getMockBuilder('\mysqli')->getMock());

        $this->query = 'SELECT * FROM table';

        $this->class = new MySQLQueryResult($this->query, $this->query_result, $this->mysqli);

        parent::baseSetUp($this->class);
    }

    /**
     * TestCase Constructor passing a MySQLi_result object with warnings.
     *
     * @return void
     */
    public function warningSetup(): void
    {
        $this->mysqli = new MockMySQLiSuccessfulWarningConnection($this->getMockBuilder('\mysqli')->getMock());

        $this->query_result = new MockMySQLiResult($this->getMockBuilder('mysqli_result')
                                                        ->disableOriginalConstructor()
                                                        ->getMock());

        $this->query = 'SELECT * FROM table';

        $this->class = new MySQLQueryResult($this->query, $this->query_result, $this->mysqli);

        parent::baseSetUp($this->class);
    }

    /**
     * TestCase Destructor.
     */
    public function tearDown(): void
    {
        unset($this->mysqli);
        unset($this->query_result);
        unset($this->query);
        unset($this->class);

        parent::tearDown();
    }

}

?>
