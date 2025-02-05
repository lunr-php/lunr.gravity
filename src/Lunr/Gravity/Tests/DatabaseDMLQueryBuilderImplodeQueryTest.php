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
class DatabaseDMLQueryBuilderImplodeQueryTest extends DatabaseDMLQueryBuilderTest
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

        $components = [ 'select_mode', 'select', 'from' ];

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
     * Test imploding a query with duplicate select_mode values.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::implode_query
     */
    public function testImplodeQueryWithDuplicateSelectModes(): void
    {
        $method = $this->getReflectionMethod('implode_query');

        $this->setReflectionPropertyValue('from', 'FROM table');
        $this->setReflectionPropertyValue('select_mode', [ 'DISTINCT', 'DISTINCT', 'SQL_CACHE' ]);

        $components = [ 'select_mode', 'select', 'from' ];

        $this->assertEquals('DISTINCT SQL_CACHE * FROM table', $method->invokeArgs($this->class, [ $components ]));
    }

    /**
     * Test imploding a query with duplicate update_mode values.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::implode_query
     */
    public function testImplodeQueryWithDuplicateUpdateModes(): void
    {
        $method = $this->getReflectionMethod('implode_query');

        $this->setReflectionPropertyValue('update', 'table1');
        $this->setReflectionPropertyValue('update_mode', [ 'LOW_PRIORITY', 'IGNORE', 'LOW_PRIORITY' ]);

        $components = [ 'update_mode', 'update' ];

        $this->assertEquals('LOW_PRIORITY IGNORE table1', $method->invokeArgs($this->class, [ $components ]));
    }

    /**
     * Test imploding a query with duplicate delete_mode values.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::implode_query
     */
    public function testImplodeQueryWithDuplicateDeleteModes(): void
    {
        $method = $this->getReflectionMethod('implode_query');

        $this->setReflectionPropertyValue('from', 'FROM table');
        $this->setReflectionPropertyValue('delete_mode', [ 'QUICK', 'IGNORE', 'QUICK' ]);

        $components = [ 'delete_mode', 'from' ];

        $this->assertEquals('QUICK IGNORE FROM table', $method->invokeArgs($this->class, [ $components ]));
    }

    /**
     * Test imploding a query with duplicate insert_mode values.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::implode_query
     */
    public function testImplodeQueryWithDuplicateInsertModes(): void
    {
        $method = $this->getReflectionMethod('implode_query');

        $this->setReflectionPropertyValue('into', 'INTO table');
        $this->setReflectionPropertyValue('insert_mode', [ 'DELAYED', 'IGNORE', 'DELAYED' ]);

        $components = [ 'insert_mode', 'into' ];

        $this->assertEquals('DELAYED IGNORE INTO table', $method->invokeArgs($this->class, [ $components ]));
    }

}

?>
