<?php

/**
 * This file contains the DatabaseDMLQueryBuilderImplodeQueryTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2012 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\Tests;

/**
 * This class contains the tests for the setup and the final query creation.
 *
 * @covers Lunr\Gravity\DatabaseDMLQueryBuilder
 */
class DatabaseDMLQueryBuilderImplodeQueryTest extends DatabaseDMLQueryBuilderTestCase
{

    /**
     * Test imploding a query with no components specified.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::implode_query
     */
    public function testImplodeQueryWithNoComponents(): void
    {
        $method = $this->getReflectionMethod('implode_query');

        $components = [];

        $this->assertEquals('', $method->invokeArgs($this->class, [ $components ]));
    }

    /**
     * Test imploding a query with non existing components specified.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::implode_query
     */
    public function testImplodeQueryWithNonExistingComponent(): void
    {
        $method = $this->getReflectionMethod('implode_query');

        $components = [ 'whatever' ];

        $this->assertEquals('', $method->invokeArgs($this->class, [ $components ]));
    }

    /**
     * Test imploding a query with existing but empty components specified.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::implode_query
     */
    public function testImplodeQueryWithExistingEmptyComponents(): void
    {
        $method = $this->getReflectionMethod('implode_query');

        $components = [ 'selectMode', 'select', 'from' ];

        $this->assertEquals('', $method->invokeArgs($this->class, [ $components ]));
    }

    /**
     * Test imploding a query with existing but empty select components specified.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::implode_query
     */
    public function testImplodeQueryWithEmptySelectComponent(): void
    {
        $method = $this->getReflectionMethod('implode_query');

        $this->setReflectionPropertyValue('from', 'FROM table');

        $components = [ 'select', 'from' ];

        $this->assertEquals('* FROM table', $method->invokeArgs($this->class, [ $components ]));
    }

    /**
     * Test imploding a query with duplicate selectMode values.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::implode_query
     */
    public function testImplodeQueryWithDuplicateSelectModes(): void
    {
        $method = $this->getReflectionMethod('implode_query');

        $this->setReflectionPropertyValue('from', 'FROM table');
        $this->setReflectionPropertyValue('selectMode', [ 'DISTINCT', 'DISTINCT', 'SQL_CACHE' ]);

        $components = [ 'selectMode', 'select', 'from' ];

        $this->assertEquals('DISTINCT SQL_CACHE * FROM table', $method->invokeArgs($this->class, [ $components ]));
    }

    /**
     * Test imploding a query with duplicate updateMode values.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::implode_query
     */
    public function testImplodeQueryWithDuplicateUpdateModes(): void
    {
        $method = $this->getReflectionMethod('implode_query');

        $this->setReflectionPropertyValue('update', 'table1');
        $this->setReflectionPropertyValue('updateMode', [ 'LOW_PRIORITY', 'IGNORE', 'LOW_PRIORITY' ]);

        $components = [ 'updateMode', 'update' ];

        $this->assertEquals('LOW_PRIORITY IGNORE table1', $method->invokeArgs($this->class, [ $components ]));
    }

    /**
     * Test imploding a query with duplicate deleteMode values.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::implode_query
     */
    public function testImplodeQueryWithDuplicateDeleteModes(): void
    {
        $method = $this->getReflectionMethod('implode_query');

        $this->setReflectionPropertyValue('from', 'FROM table');
        $this->setReflectionPropertyValue('deleteMode', [ 'QUICK', 'IGNORE', 'QUICK' ]);

        $components = [ 'deleteMode', 'from' ];

        $this->assertEquals('QUICK IGNORE FROM table', $method->invokeArgs($this->class, [ $components ]));
    }

    /**
     * Test imploding a query with duplicate insertMode values.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::implode_query
     */
    public function testImplodeQueryWithDuplicateInsertModes(): void
    {
        $method = $this->getReflectionMethod('implode_query');

        $this->setReflectionPropertyValue('into', 'INTO table');
        $this->setReflectionPropertyValue('insertMode', [ 'DELAYED', 'IGNORE', 'DELAYED' ]);

        $components = [ 'insertMode', 'into' ];

        $this->assertEquals('DELAYED IGNORE INTO table', $method->invokeArgs($this->class, [ $components ]));
    }

}

?>
