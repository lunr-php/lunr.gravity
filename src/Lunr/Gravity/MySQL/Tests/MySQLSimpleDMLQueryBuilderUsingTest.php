<?php

/**
 * This file contains the MySQLSimpleDMLQueryBuilderUsingTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2016 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MySQL\Tests;

/**
 * This class contains select tests for the MySQLSimpleDMLQueryBuilder class.
 *
 * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder
 */
class MySQLSimpleDMLQueryBuilderUsingTest extends MySQLSimpleDMLQueryBuilderTestCase
{

    /**
     * Test using().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::using
     */
    public function testUsing(): void
    {
        $this->escaper->expects('column')
                      ->once()
                      ->with('column1')
                      ->andReturn('`column1`');

        $this->escaper->expects('table')
                      ->once()
                      ->with('table2')
                      ->andReturn('`table2`');

        $this->builder->expects($this->once())
                      ->method('using')
                      ->with('`column1`')
                      ->willReturnSelf();

        $this->class->join('table2');
        $this->class->using('column1');
    }

    /**
     * Test using().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::using
     */
    public function testUsingAddSecondColumn(): void
    {
        $this->escaper->expects('table')
                      ->once()
                      ->with('table2')
                      ->andReturn('`table2`');

        $this->escaper->expects('column')
                      ->once()
                      ->with('column1')
                      ->andReturn('`column1`');
        $this->escaper->expects('column')
                      ->once()
                      ->with('column2')
                      ->andReturn('`column2`');

        $this->builder->expects($this->exactly(2))
                      ->method('using')
                      ->willReturnSelf();

        $this->class->join('table2');
        $this->class->using('column1');
        $this->class->using('column2');
    }

    /**
     * Test using().
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::using
     */
    public function testUsingMultipleColumn(): void
    {
        $this->escaper->expects('table')
                      ->once()
                      ->with('table2')
                      ->andReturn('`table2`');

        $this->escaper->expects('column')
                      ->once()
                      ->with('column1')
                      ->andReturn('`column1`');
        $this->escaper->expects('column')
                      ->once()
                      ->with('column2')
                      ->andReturn('`column2`');

        $this->builder->expects($this->once())
                      ->method('using')
                      ->with('`column1`, `column2`')
                      ->willReturnSelf();

        $this->class->join('table2');
        $this->class->using('column1, column2');
    }

}

?>
