<?php

/**
 * Contains SQLite3QueryResultTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2012 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\SQLite3\Tests;

use Lunr\Gravity\SQLite3\SQLite3QueryResult;
use Lunr\Halo\LunrBaseTestCase;
use SQLite3;
use SQLite3Result;

/**
 * This class contains common constructors/destructors and data providers
 * for testing the SQLite3QueryResult class.
 *
 * @covers Lunr\Gravity\SQLite3\SQLite3QueryResult
 */
abstract class SQLite3QueryResultTestCase extends LunrBaseTestCase
{

    /**
     * Instance of the SQLite3 class.
     * @var SQLite3
     */
    protected $sqlite3;

    /**
     * The executed query.
     * @var string
     */
    protected $query;

    /**
     * Instance of the SQLite3Result class.
     * @var SQLite3Result
     */
    protected $sqlite3_result;

    /**
     * Instance of the tested class.
     * @var SQLite3QueryResult
     */
    protected SQLite3QueryResult $class;

    /**
     * TestCase Constructor passing a SQLite3Result object.
     *
     * @return void
     */
    public function setUpWithResult(): void
    {
        $this->sqlite3 = $this->getMockBuilder('Lunr\Gravity\SQLite3\LunrSQLite3')->getMock();

        $this->query = 'SELECT * FROM table';

        $this->sqlite3_result = $this->getMockBuilder('SQLite3Result')
                                     ->disableOriginalConstructor()
                                     ->getMock();

        $this->class = new SQLite3QueryResult($this->query, $this->sqlite3_result, $this->sqlite3);

        parent::baseSetUp($this->class);
    }

    /**
     * TestCase Constructor with a TRUE instead of a SQLite3Result object.
     *
     * @return void
     */
    public function setUpWithNoResult(): void
    {
        $this->sqlite3 = $this->getMockBuilder('Lunr\Gravity\SQLite3\LunrSQLite3')->getMock();

        $this->query = 'SELECT * FROM table';

        $this->sqlite3_result = TRUE;

        $this->class = new SQLite3QueryResult($this->query, $this->sqlite3_result, $this->sqlite3);

        parent::baseSetUp($this->class);

        $this->setReflectionPropertyValue('affected_rows', 12);
        $this->setReflectionPropertyValue('insert_id', 0);
        $this->setReflectionPropertyValue('error_message', '');
        $this->setReflectionPropertyValue('error_number', 0);
    }

    /**
     * TestCase Constructor with a FALSE instead of a SQLite3Result object.
     *
     * @return void
     */
    public function setUpWithFailedQuery(): void
    {
        $this->sqlite3 = $this->getMockBuilder('Lunr\Gravity\SQLite3\LunrSQLite3')->getMock();

        $this->query = 'SELECT * FROM table';

        $this->sqlite3_result = FALSE;

        $this->class = new SQLite3QueryResult($this->query, $this->sqlite3_result, $this->sqlite3);

        parent::baseSetUp($this->class);

        $this->setReflectionPropertyValue('error_message', 'The query failed.');
        $this->setReflectionPropertyValue('error_number', 8);
    }

    /**
     * TestCase Destructor.
     */
    public function tearDown(): void
    {
        unset($this->sqlite3);
        unset($this->query);
        unset($this->sqlite3_result);
        unset($this->class);

        parent::tearDown();
    }

}

?>
