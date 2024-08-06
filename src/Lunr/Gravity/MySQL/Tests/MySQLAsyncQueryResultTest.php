<?php

/**
 * This file contains the MySQLAsyncQueryResultTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2012 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MySQL\Tests;

use Lunr\Gravity\MySQL\MySQLAsyncQueryResult;
use Lunr\Halo\LunrBaseTest;
use mysqli;

/**
 * This class contains common constructors/destructors and data providers
 * for testing the MySQLAsyncQueryResult class.
 *
 * @covers Lunr\Gravity\MySQL\MySQLAsyncQueryResult
 */
abstract class MySQLAsyncQueryResultTest extends LunrBaseTest
{

    /**
     * Mock instance of the mysqli class.
     * @var mysqli
     */
    protected $mysqli;

    /**
     * The executed query.
     * @var string
     */
    protected $query;

    /**
     * Instance of the tested class.
     * @var MySQLAsyncQueryResult
     */
    protected MySQLAsyncQueryResult $class;

    /**
     * TestCase Constructor passing TRUE as query result.
     */
    public function setUp(): void
    {
        $this->mysqli = new MockMySQLiSuccessfulConnection($this->getMockBuilder('\mysqli')->getMock());

        $this->query = 'SELECT * FROM table';

        $this->class = new MySQLAsyncQueryResult($this->query, $this->mysqli);

        parent::baseSetUp($this->class);
    }

    /**
     * TestCase Destructor.
     */
    public function tearDown(): void
    {
        unset($this->mysqli);
        unset($this->class);
        unset($this->query);

        parent::tearDown();
    }

}

?>
