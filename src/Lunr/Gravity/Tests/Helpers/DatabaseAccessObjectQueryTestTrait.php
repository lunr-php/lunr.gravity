<?php

/**
 * This file contains the DatabaseAccessObjectSelectQueryTestTrait.
 *
 * SPDX-FileCopyrightText: Copyright 2014 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\Tests\Helpers;

use Lunr\Halo\FluidInterfaceMock;

/**
 * This trait contains helper methods to test general success and error cases of SELECT queries.
 */
trait DatabaseAccessObjectQueryTestTrait
{

    /**
     * Expect that a query returns successful results.
     *
     * @param mixed  $data   Result data
     * @param string $format Return result as 'array', 'row', 'column' or 'cell'
     *
     * @return void
     */
    public function expectResultOnSuccess($data, $format = 'array'): void
    {
        $mock = new FluidInterfaceMock();

        $this->db->expects($this->atLeast(1))
                 ->method('get_new_dml_query_builder_object')
                 ->willReturn($mock);

        $this->db->expects($this->once())
                 ->method('query')
                 ->willReturn($this->result);

        $this->result->expects($this->once())
                     ->method('has_failed')
                     ->willReturn(FALSE);

        $count = $format === 'cell' ? 1 : count($data);

        $this->result->expects($this->once())
                     ->method('number_of_rows')
                     ->willReturn($count);

        $this->result->expects($this->once())
                     ->method('result_' . $format)
                     ->willReturn($data);
    }

    /**
     * Expect that a query returns no results.
     *
     * @param string $format Return result as 'array', 'row', 'column' or 'cell'
     *
     * @return void
     */
    public function expectNoResultsFound($format = 'array'): void
    {
        $mock = new FluidInterfaceMock();

        $this->db->expects($this->atLeast(1))
                 ->method('get_new_dml_query_builder_object')
                 ->willReturn($mock);

        $this->db->expects($this->once())
                 ->method('query')
                 ->willReturn($this->result);

        $this->result->expects($this->once())
                     ->method('has_failed')
                     ->willReturn(FALSE);

        $this->result->expects($this->once())
                     ->method('number_of_rows')
                     ->willReturn(0);

        $this->result->expects($this->never())
                     ->method('result_' . $format);
    }

    /**
     * Expect that a query returns an error.
     *
     * @return void
     */
    protected function expectQueryError(): void
    {
        $mock = new FluidInterfaceMock();

        $this->db->expects($this->atLeast(1))
                 ->method('get_new_dml_query_builder_object')
                 ->willReturn($mock);

        $this->db->expects($this->once())
                 ->method('query')
                 ->willReturn($this->result);

        $this->result->expects($this->once())
                     ->method('has_failed')
                     ->willReturn(TRUE);

        $this->result->expects($this->once())
                     ->method('error_number')
                     ->willReturn(1);

        $this->result->expects($this->exactly(2))
                     ->method('error_message')
                     ->willReturn('Error!');

        $this->result->expects($this->exactly(2))
                     ->method('query')
                     ->willReturn('QUERY;');

        $this->result->expects($this->any())
                     ->method('has_deadlock')
                     ->willReturn(FALSE);

        $this->result->expects($this->any())
                     ->method('has_lock_timeout')
                     ->willReturn(FALSE);

        $this->expectException('Lunr\Gravity\Exceptions\QueryException');
        $this->expectExceptionMessage('Database query error!');
    }

    /**
     * Expect that a query is successful.
     *
     * @return void
     */
    protected function expectQuerySuccess(): void
    {
        $mock = new FluidInterfaceMock();

        $this->db->expects($this->atLeast(1))
                 ->method('get_new_dml_query_builder_object')
                 ->willReturn($mock);

        $this->db->expects($this->once())
                 ->method('query')
                 ->willReturn($this->result);

        $this->result->expects($this->once())
                     ->method('has_failed')
                     ->willReturn(FALSE);

        $this->result->expects($this->any())
                     ->method('has_deadlock')
                     ->willReturn(FALSE);

        $this->result->expects($this->any())
                     ->method('has_lock_timeout')
                     ->willReturn(FALSE);
    }

    /**
     * Expect that a query is successful, after a deadlock-caused retry.
     *
     * @return void
     */
    protected function expectQuerySuccessAfterRetry(): void
    {
        $mock = new FluidInterfaceMock();

        $this->db->expects($this->atLeast(1))
                 ->method('get_new_dml_query_builder_object')
                 ->willReturn($mock);

        $this->db->expects($this->exactly(2))
                 ->method('query')
                 ->willReturn($this->result);

        $this->result->expects($this->once())
                     ->method('has_failed')
                     ->willReturnOnConsecutiveCalls(FALSE);

        $this->result->expects($this->exactly(2))
                     ->method('has_deadlock')
                     ->willReturnOnConsecutiveCalls(TRUE, FALSE);

        $this->result->expects($this->once())
                     ->method('has_lock_timeout')
                     ->willReturnOnConsecutiveCalls(FALSE);
    }

}

?>
