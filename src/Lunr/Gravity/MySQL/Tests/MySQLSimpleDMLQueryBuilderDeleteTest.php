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
        $this->escaper->expects($this->once())
                      ->method('table')
                      ->with($this->equalTo('col'))
                      ->will($this->returnValue('`col`'));

        $this->builder->expects($this->once())
                      ->method('delete')
                      ->with($this->equalTo('`col`'))
                      ->will($this->returnSelf());

        $this->class->delete('col');
    }

    /**
     * Test delete() with multiple input columns.
     *
     * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::delete
     */
    public function testDeleteWithMultipleColumns(): void
    {
        $this->escaper->expects($this->exactly(2))
                      ->method('table')
                      ->withConsecutive([ 'col' ], [ ' col' ])
                      ->will($this->returnValue('`col`'));

        $this->builder->expects($this->once())
                      ->method('delete')
                      ->with($this->equalTo('`col`, `col`'))
                      ->willReturnSelf();

        $this->class->delete('col, col');
    }

}

?>
