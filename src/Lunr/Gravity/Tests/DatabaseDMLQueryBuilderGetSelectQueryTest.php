<?php

/**
 * This file contains the DatabaseDMLQueryBuilderGetSelectQueryTest class.
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
class DatabaseDMLQueryBuilderGetSelectQueryTest extends DatabaseDMLQueryBuilderTestCase
{

    /**
     * Test getting a select query.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderImplodeQueryTest::testImplodeQueryWithEmptySelectComponent
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderImplodeQueryTest::testImplodeQueryWithDuplicateSelectModes
     * @covers  Lunr\Gravity\DatabaseDMLQueryBuilder::get_select_query
     */
    public function testGetSelectQuery(): void
    {
        $this->setReflectionPropertyValue('from', 'FROM table');
        $this->setReflectionPropertyValue('select_mode', [ 'DISTINCT', 'SQL_CACHE' ]);
        $this->setReflectionPropertyValue('select', 'col');
        $this->setReflectionPropertyValue('join', 'INNER JOIN table1');
        $this->setReflectionPropertyValue('where', 'WHERE a = b');
        $this->setReflectionPropertyValue('order_by', 'ORDER BY col ASC');
        $this->setReflectionPropertyValue('group_by', 'GROUP BY col');
        $this->setReflectionPropertyValue('having', 'HAVING a = b');
        $this->setReflectionPropertyValue('limit', 'LIMIT 1');
        $this->setReflectionPropertyValue('lock_mode', 'FOR UPDATE');

        $string  = 'SELECT DISTINCT SQL_CACHE col FROM table INNER JOIN table1 WHERE a = b ';
        $string .= 'GROUP BY col HAVING a = b ORDER BY col ASC LIMIT 1 FOR UPDATE';

        $this->assertEquals($string, $this->class->get_select_query());
    }

    /**
     * Test getting a select query with undefined from clause.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderImplodeQueryTest::testImplodeQueryWithEmptySelectComponent
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderImplodeQueryTest::testImplodeQueryWithDuplicateSelectModes
     * @covers  Lunr\Gravity\DatabaseDMLQueryBuilder::get_select_query
     */
    public function testGetSelectQueryWithUndefinedFromClause(): void
    {
        $this->setReflectionPropertyValue('select_mode', [ 'DISTINCT', 'SQL_CACHE' ]);
        $this->setReflectionPropertyValue('select', 'col');

        $string = 'SELECT DISTINCT SQL_CACHE col';

        $this->assertEquals($string, $this->class->get_select_query());
    }

    /**
    * Test getting a select query when the compound property (UNION, INTERSECT or EXCEPT) is set.
    *
    * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderImplodeQueryTest::testImplodeQueryWithEmptySelectComponent
    * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderImplodeQueryTest::testImplodeQueryWithDuplicateSelectModes
    * @covers  Lunr\Gravity\DatabaseDMLQueryBuilder::get_select_query
    */
    public function testGetSelectQueryWithCompoundConnector(): void
    {
        $this->setReflectionPropertyValue('from', 'FROM table');
        $this->setReflectionPropertyValue('select', 'col');
        $this->setReflectionPropertyValue('compound', 'UNION (SELECT col2 FROM table2)');

        $string = '(SELECT col FROM table) UNION (SELECT col2 FROM table2)';

        $this->assertEquals($string, $this->class->get_select_query());
    }

    /**
     * Test getting a select query using with statement
     */
    public function testGetSelectQueryUsingWith(): void
    {

        $this->setReflectionPropertyValue('with', 'alias AS ( query )');
        $this->setReflectionPropertyValue('select', '*');
        $this->setReflectionPropertyValue('from', 'FROM alias');

        $string = 'WITH alias AS ( query ) SELECT * FROM alias';

        $this->assertEquals($string, $this->class->get_select_query());
    }

    /**
     * Test getting a select query using a recursive with statement
     */
    public function testGetSelectQueryUsingRecursiveWith(): void
    {
        $this->setReflectionPropertyValue('with', 'alias AS ( query )');
        $this->setReflectionPropertyValue('is_recursive', TRUE);
        $this->setReflectionPropertyValue('select', '*');
        $this->setReflectionPropertyValue('from', 'FROM alias');

        $string = 'WITH RECURSIVE alias AS ( query ) SELECT * FROM alias';

        $this->assertEquals($string, $this->class->get_select_query());
    }

}

?>
