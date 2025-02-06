<?php

/**
 * This file contains the DatabaseDMLQueryBuilderQueryPartsInsertTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2012 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\Tests;

/**
 * This class contains the tests for the query parts methods that are used when building INSERT and REPLACE statements
 *
 * @covers Lunr\Gravity\DatabaseDMLQueryBuilder
 */
class DatabaseDMLQueryBuilderQueryPartsUpdateTest extends DatabaseDMLQueryBuilderTestCase
{

    /**
     * Test specifying the SET part of a query.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_update
     */
    public function testInitialUpdate(): void
    {
        $method = $this->getReflectionMethod('sql_update');

        $method->invokeArgs($this->class, [ 'table1' ]);

        $string = 'table1';

        $this->assertPropertyEquals('update', $string);
    }

    /**
     * Test specifying the SET part of a query incrementally.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_update
     */
    public function testIncrementalUpdate(): void
    {
        $method = $this->getReflectionMethod('sql_update');

        $method->invokeArgs($this->class, [ 'table1' ]);
        $method->invokeArgs($this->class, [ 'table2' ]);

        $string = 'table1, table2';

        $this->assertPropertyEquals('update', $string);
    }

}
?>
