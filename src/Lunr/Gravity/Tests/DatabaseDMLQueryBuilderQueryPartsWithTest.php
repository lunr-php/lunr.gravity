<?php

/**
 * This file contains the DatabaseDMLQueryBuilderQueryPartsWithTest class.
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
class DatabaseDMLQueryBuilderQueryPartsWithTest extends DatabaseDMLQueryBuilderTestCase
{

    /**
     * Test specifying the with part of a query without recursion and without column names
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_with
     */
    public function testNonRecursiveWithWithoutColumnNames(): void
    {
        $method = $this->getReflectionMethod('sql_with');
        $method->invokeArgs($this->class, [ 'alias', 'query' ]);

        $string = 'alias AS ( query )';

        $this->assertEquals($string, $this->getReflectionPropertyValue('with'));
    }

    /**
     * Test specifying multiple with statements in a query without recursion and without column names
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_with
     */
    public function testMultipleNonRecursiveWithWithoutColumnNames(): void
    {
        $this->setReflectionPropertyValue('with', 'alias AS ( query )');

        $method = $this->getReflectionMethod('sql_with');
        $method->invokeArgs($this->class, [ 'alias2', 'query2' ]);

        $string = 'alias AS ( query ), alias2 AS ( query2 )';

        $this->assertEquals($string, $this->getReflectionPropertyValue('with'));
    }

    /**
     * Test specifying the with part of a query without recursion but with column names
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_with
     */
    public function testNonRecursiveWithIncludingColumnNames(): void
    {
        $method = $this->getReflectionMethod('sql_with');

        $columnNames = [ 'column1', 'column2', 'column3' ];

        $method->invokeArgs($this->class, [ 'alias', 'query', '', '', $columnNames ]);

        $string = 'alias (column1, column2, column3) AS ( query )';

        $this->assertEquals($string, $this->getReflectionPropertyValue('with'));
    }

    /**
     * Test specifying multiple with statements in a query without recursion and with column names
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_with
     */
    public function testMultipleNonRecursiveWithIncludingColumnNames(): void
    {
        $this->setReflectionPropertyValue
            ('with', 'alias (column1, column2, column3) AS ( query )');

        $method = $this->getReflectionMethod('sql_with');
        $method->invokeArgs($this->class, [ 'alias2', 'query2' ]);

        $string = 'alias (column1, column2, column3) AS ( query ), alias2 AS ( query2 )';

        $this->assertEquals($string, $this->getReflectionPropertyValue('with'));
    }

    /**
     * Test specifying the with part of a query with recursion and without column names
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_with
     */
    public function testRecursiveWithWithoutColumnNames(): void
    {
        $method = $this->getReflectionMethod('sql_with');
        $method->invokeArgs($this->class, [ 'alias', 'anchor_query', 'recursive_query', 'UNION' ]);

        $string = 'alias AS ( anchor_query UNION recursive_query )';

        $this->assertEquals($string, $this->getReflectionPropertyValue('with'));
    }

    /**
     * Test specifying the with part of a query with recursion and with column names
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_with
     */
    public function testRecursiveWithWithColumnNames(): void
    {
        $method = $this->getReflectionMethod('sql_with');

        $columnNames = [ 'column1', 'column2', 'column3' ];

        $method->invokeArgs($this->class, [ 'alias', 'anchor_query', 'recursive_query', 'UNION', $columnNames ]);

        $string = 'alias (column1, column2, column3) AS ( anchor_query UNION recursive_query )';

        $this->assertEquals($string, $this->getReflectionPropertyValue('with'));
    }

    /**
     * Test specifying a recursive query after a  non recursive query has been specified without column names
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_with
     */
    public function testRecursiveWithAfterNonRecursiveQueryWithoutColumnNames(): void
    {
        $this->setReflectionPropertyValue
        ('with', 'alias AS ( query )');

        $method = $this->getReflectionMethod('sql_with');

        $method->invokeArgs($this->class, [ 'alias', 'anchor_query', 'recursive_query', 'UNION' ]);

        $string = 'alias AS ( anchor_query UNION recursive_query ), alias AS ( query )';

        $this->assertEquals($string, $this->getReflectionPropertyValue('with'));
    }

    /**
     * Test specifying a recursive query after a non recursive query has been specified using column names
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_with
     */
    public function testRecursiveWithAfterNonRecursiveQueryWithColumnNames(): void
    {
        $this->setReflectionPropertyValue
        ('with', 'alias (column1, column2, column3) AS ( query )');

        $columnNames = [ 'column1', 'column2', 'column3' ];

        $method = $this->getReflectionMethod('sql_with');

        $method->invokeArgs($this->class, [ 'alias', 'anchor_query', 'recursive_query', 'UNION', $columnNames ]);

        $string  = 'alias (column1, column2, column3) AS ( anchor_query UNION recursive_query ),';
        $string .= ' alias (column1, column2, column3) AS ( query )';

        $this->assertEquals($string, $this->getReflectionPropertyValue('with'));
    }

    /**
     * Test specifying a recursive query after a recursive query has been specified without column names
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_with
     */
    public function testRecursiveWithAfterRecursiveQueryWithoutColumnNames(): void
    {
        $this->setReflectionPropertyValue
        ('with', 'alias AS ( anchor_query UNION recursive_query )');

        $method = $this->getReflectionMethod('sql_with');

        $method->invokeArgs($this->class, [ 'alias', 'anchor_query', 'recursive_query', 'UNION' ]);

        $string = 'alias AS ( anchor_query UNION recursive_query ), alias AS ( anchor_query UNION recursive_query )';

        $this->assertEquals($string, $this->getReflectionPropertyValue('with'));
    }

    /**
     * Test specifying a recursive query after a recursive query has been specified using column names
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_with
     */
    public function testRecursiveWithAfterRecursiveQueryWithColumnNames(): void
    {
        $this->setReflectionPropertyValue
        ('with', 'alias (column1, column2, column3) AS ( anchor_query UNION recursive_query )');

        $columnNames = [ 'column1', 'column2', 'column3' ];

        $method = $this->getReflectionMethod('sql_with');

        $method->invokeArgs($this->class, [ 'alias', 'anchor_query', 'recursive_query', 'UNION', $columnNames ]);

        $string  = 'alias (column1, column2, column3) AS ( anchor_query UNION recursive_query ), ';
        $string .= 'alias (column1, column2, column3) AS ( anchor_query UNION recursive_query )';

        $this->assertEquals($string, $this->getReflectionPropertyValue('with'));
    }

    /**
     * Test specifying a non recursive query after a recursive query has been specified without column names
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_with
     */
    public function testNonRecursiveWithAfterRecursiveQueryWithoutColumnNames(): void
    {
        $this->setReflectionPropertyValue
        ('with', 'alias AS ( anchor_query UNION recursive_query )');

        $method = $this->getReflectionMethod('sql_with');

        $method->invokeArgs($this->class, [ 'alias', 'query' ]);

        $string = 'alias AS ( anchor_query UNION recursive_query ), alias AS ( query )';

        $this->assertEquals($string, $this->getReflectionPropertyValue('with'));
    }

    /**
     * Test specifying a non recursive query after a recursive query has been specified using column names
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_with
     */
    public function testNonRecursiveWithAfterRecursiveQueryWithColumnNames(): void
    {
        $this->setReflectionPropertyValue
        ('with', 'alias (column1, column2, column3) AS ( anchor_query UNION recursive_query )');

        $method = $this->getReflectionMethod('sql_with');

        $columnNames = [ 'column1', 'column2', 'column3' ];

        $method->invokeArgs($this->class, [ 'alias', 'query', '', '', $columnNames ]);

        $string  = 'alias (column1, column2, column3) AS ( anchor_query UNION recursive_query ), ';
        $string .= 'alias (column1, column2, column3) AS ( query )';

        $this->assertEquals($string, $this->getReflectionPropertyValue('with'));
    }

}

?>
