<?php

/**
 * This file contains the QueryExceptionTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2019 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\Exceptions\Tests;

use Lunr\Gravity\DatabaseQueryResultInterface;
use Lunr\Gravity\Exceptions\QueryException;
use Lunr\Halo\LunrBaseTestCase;

/**
 * This class contains common setup routines, providers
 * and shared attributes for testing the QueryException class.
 *
 * @covers Lunr\Gravity\Exceptions\QueryException
 */
abstract class QueryExceptionTest extends LunrBaseTestCase
{

    /**
     * Mock instance of a query result.
     * @var DatabaseQueryResultInterface
     */
    protected $result;

    /**
     * Instance of the tested class.
     * @var QueryException
     */
    protected QueryException $class;

    /**
     * TestCase Constructor.
     */
    public function setUp(): void
    {
        $this->result = $this->getMockBuilder('Lunr\Gravity\DatabaseQueryResultInterface')
                             ->disableOriginalConstructor()
                             ->getMock();

        $this->result->expects($this->once())
                     ->method('query')
                     ->willReturn('SQL query');

        $this->result->expects($this->once())
                     ->method('error_number')
                     ->willReturn(1024);

        $this->result->expects($this->once())
                     ->method('error_message')
                     ->willReturn("There's an error in your query.");

        $this->class = new QueryException($this->result, 'Exception Message');

        parent::baseSetUp($this->class);
    }

    /**
     * TestCase Destructor.
     */
    public function tearDown(): void
    {
        unset($this->result);
        unset($this->class);

        parent::tearDown();
    }

}

?>
