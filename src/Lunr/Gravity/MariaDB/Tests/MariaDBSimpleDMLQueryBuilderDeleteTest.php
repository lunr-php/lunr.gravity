<?php

/**
 * This file contains the MariaDBSimpleDMLQueryBuilderDeleteTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2018 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MariaDB\Tests;

/**
 * This class contains the delete tests for the MariaDBSimpleDMLQueryBuilder class
 *
 * @covers Lunr\Gravity\MariaDB\MariaDBSimpleDMLQueryBuilder
 */
class MariaDBSimpleDMLQueryBuilderDeleteTest extends MariaDBSimpleDMLQueryBuilderTestCase
{

    /**
     * Test returning with a single column.
     *
     * @covers Lunr\Gravity\MariaDB\MariaDBSimpleDMLQueryBuilder::returning
     */
    public function testDeleteReturningSingleColumn(): void
    {
        $this->escaper->expects($this->once())
                      ->method('result_column')
                      ->with('id')
                      ->willReturn('`id`');

        $this->builder->expects($this->once())
                      ->method('returning')
                      ->with('`id`')
                      ->willReturnSelf();

        $this->class->returning('id');
    }

    /**
     * Test returning with multiple columns.
     *
     * @covers Lunr\Gravity\MariaDB\MariaDBSimpleDMLQueryBuilder::returning
     */
    public function testDeleteReturningMultipleColumns(): void
    {
        $this->escaper->expects($this->exactly(2))
                      ->method('result_column')
                      ->willReturnOnConsecutiveCalls('`id`', '`name`');

        $this->builder->expects($this->once())
                      ->method('returning')
                      ->with('`id`, `name`')
                      ->willReturnSelf();

        $this->class->returning('id, name');
    }

}

?>
