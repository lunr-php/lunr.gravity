<?php

/**
 * This file contains the SQLDMLQueryBuilderInsertTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2013 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\Tests;

/**
 * This class contains the tests for the query parts necessary to build
 * insert/replace queries.
 *
 * @covers Lunr\Gravity\SQLDMLQueryBuilder
 */
class SQLDMLQueryBuilderInsertTest extends SQLDMLQueryBuilderTestCase
{

    /**
     * Test fluid interface of the select_statement method.
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::select_statement
     */
    public function testSelectStatementReturnsSelfReference(): void
    {
        $return = $this->class->select_statement('SELECT * FROM table');

        $this->assertInstanceOf('Lunr\Gravity\SQLDMLQueryBuilder', $return);
        $this->assertSame($this->class, $return);
    }

    /**
     * Test fluid interface of the into method.
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::into
     */
    public function testIntoReturnsSelfReference(): void
    {
        $return = $this->class->into('table');

        $this->assertInstanceOf('Lunr\Gravity\SQLDMLQueryBuilder', $return);
        $this->assertSame($this->class, $return);
    }

    /**
     * Test fluid interface of the column_names method.
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::column_names
     */
    public function testColumnNamesReturnsSelfReference(): void
    {
        $return = $this->class->column_names([ 'column1' ]);

        $this->assertInstanceOf('Lunr\Gravity\SQLDMLQueryBuilder', $return);
        $this->assertSame($this->class, $return);
    }

    /**
     * Test fluid interface of the values method.
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::values
     */
    public function testValuesReturnsSelfReference(): void
    {
        $return = $this->class->values([ 'value1' ]);

        $this->assertInstanceOf('Lunr\Gravity\SQLDMLQueryBuilder', $return);
        $this->assertSame($this->class, $return);
    }

}

?>
