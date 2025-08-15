<?php

/**
 * This file contains the MySQLSimpleDMLQueryBuilderDeleteTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2013 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MySQL\Tests;

/**
 * This class contains select tests for the MySQLSimpleDMLQueryBuilder class.
 *
 * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder
 */
class MySQLSimpleDMLQueryBuilderDeleteTest extends MySQLSimpleDMLQueryBuilderTestCase
{

    /**
     * Test delete() with one input column.
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::delete
     */
    public function testDeleteWithOneColumn(): void
    {
        $this->escaper->expects('table')
                      ->once()
                      ->with('col')
                      ->andReturn('`col`');

        $this->builder->expects($this->once())
                      ->method('delete')
                      ->with('`col`')
                      ->willReturnSelf();

        $this->class->delete('col');
    }

    /**
     * Test delete() with multiple input columns.
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::delete
     */
    public function testDeleteWithMultipleColumns(): void
    {
        $this->escaper->expects('table')
                      ->twice()
                      ->with( 'col')
                      ->andReturn('`col`');

        $this->builder->expects($this->once())
                      ->method('delete')
                      ->with('`col`, `col`')
                      ->willReturnSelf();

        $this->class->delete('col,col');
    }

}

?>
