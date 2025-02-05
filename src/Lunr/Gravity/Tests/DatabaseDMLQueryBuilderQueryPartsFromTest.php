<?php

/**
 * This file contains the DatabaseDMLQueryBuilderQueryPartsFromTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2012 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\Tests;

/**
 * This class contains the tests for the query parts methods.
 *
 * @covers Lunr\Gravity\DatabaseDMLQueryBuilder
 */
class DatabaseDMLQueryBuilderQueryPartsFromTest extends DatabaseDMLQueryBuilderTestCase
{

    /**
     * Test specifying the from part of a query without index hints.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_from
     */
    public function testFromWithoutIndexHints(): void
    {
        $method = $this->getReflectionMethod('sql_from');

        $method->invokeArgs($this->class, [ 'table' ]);

        $string = 'FROM table';

        $this->assertEquals($string, $this->getReflectionPropertyValue('from'));
    }

    /**
     * Test specifying the from part of a query with single index hint.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderBaseTest::testPrepareValidIndexHints
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderBaseTest::testPrepareInvalidIndexHintsReturnsEmptyString
     * @covers  Lunr\Gravity\DatabaseDMLQueryBuilder::sql_from
     */
    public function testFromWithSingleIndexHint(): void
    {
        $method = $this->getReflectionMethod('sql_from');
        $hints  = [ 'index_hint' ];

        $method->invokeArgs($this->class, [ 'table', $hints ]);

        $string = 'FROM table index_hint';

        $this->assertEquals($string, $this->getReflectionPropertyValue('from'));
    }

    /**
     * Test specifying the from part of a query with multiple index hints.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderBaseTest::testPrepareValidIndexHints
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderBaseTest::testPrepareInvalidIndexHintsReturnsEmptyString
     * @covers  Lunr\Gravity\DatabaseDMLQueryBuilder::sql_from
     */
    public function testFromWithMultipleIndexHints(): void
    {
        $method = $this->getReflectionMethod('sql_from');
        $hints  = [ 'index_hint', 'index_hint' ];

        $method->invokeArgs($this->class, [ 'table', $hints ]);

        $string = 'FROM table index_hint, index_hint';

        $this->assertEquals($string, $this->getReflectionPropertyValue('from'));
    }

    /**
     * Test specifying the from part of a query with null index hints.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderBaseTest::testPrepareValidIndexHints
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderBaseTest::testPrepareInvalidIndexHintsReturnsEmptyString
     * @covers  Lunr\Gravity\DatabaseDMLQueryBuilder::sql_from
     */
    public function testFromWithNullIndexHints(): void
    {
        $method = $this->getReflectionMethod('sql_from');
        $hints  = [ NULL, NULL ];

        $method->invokeArgs($this->class, [ 'table', $hints ]);

        $string = 'FROM table ';

        $this->assertEquals($string, $this->getReflectionPropertyValue('from'));
    }

    /**
     * Test specifying more than one table in FROM (cartesian product).
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_from
     */
    public function testIncrementalFromWithoutIndices(): void
    {
        $method = $this->getReflectionMethod('sql_from');

        $method->invokeArgs($this->class, [ 'table' ]);
        $method->invokeArgs($this->class, [ 'table' ]);

        $string = 'FROM table, table';

        $this->assertEquals($string, $this->getReflectionPropertyValue('from'));
    }

    /**
     * Test specifying more than one table in FROM (cartesian product).
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderBaseTest::testPrepareValidIndexHints
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderBaseTest::testPrepareInvalidIndexHintsReturnsEmptyString
     * @covers  Lunr\Gravity\DatabaseDMLQueryBuilder::sql_from
     */
    public function testIncrementalFromWithIndices(): void
    {
        $method = $this->getReflectionMethod('sql_from');
        $hints  = [ 'index_hint' ];

        $method->invokeArgs($this->class, [ 'table', $hints ]);
        $method->invokeArgs($this->class, [ 'table', $hints ]);

        $string = 'FROM table index_hint, table index_hint';

        $this->assertEquals($string, $this->getReflectionPropertyValue('from'));
    }

}

?>
