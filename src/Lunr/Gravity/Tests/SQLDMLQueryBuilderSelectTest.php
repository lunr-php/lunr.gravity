<?php

/**
 * This file contains the SQLDMLQueryBuilderSelectTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2013 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\Tests;

/**
 * This class contains the tests for the query parts necessary to build
 * select queries.
 *
 * @covers Lunr\Gravity\SQLDMLQueryBuilder
 */
class SQLDMLQueryBuilderSelectTest extends SQLDMLQueryBuilderTestCase
{

    /**
     * Test specifying the SELECT part of a query.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsSelectTest::testInitialSelect
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsSelectTest::testIncrementalSelect
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::select
     */
    public function testSelect(): void
    {
        $this->class->select('col');
        $value = $this->getReflectionPropertyValue('select');

        $this->assertEquals('col', $value);
    }

    /**
     * Test fluid interface of the select method.
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::select
     */
    public function testSelectReturnsSelfReference(): void
    {
        $return = $this->class->select('col');

        $this->assertInstanceOf('Lunr\Gravity\SQLDMLQueryBuilder', $return);
        $this->assertSame($this->class, $return);
    }

    /**
     * Test specifying the FROM part of a query.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsFromTest::testFromWithoutIndexHints
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::from
     */
    public function testFromWithoutIndexHints(): void
    {
        $this->class->from('table');
        $value = $this->getReflectionPropertyValue('from');

        $this->assertEquals('FROM table', $value);
    }

    /**
     * Test specifying the FROM part of a query.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsFromTest::testFromWithSingleIndexHint
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsFromTest::testFromWithMultipleIndexHints
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::from
     */
    public function testFromWithIndexHints(): void
    {
        $hints = [ 'index_hint' ];
        $this->class->from('table', $hints);
        $value = $this->getReflectionPropertyValue('from');

        $this->assertEquals('FROM table index_hint', $value);
    }

    /**
     * Test fluid interface of the from method.
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::from
     */
    public function testFromReturnsSelfReference(): void
    {
        $return = $this->class->from('table');

        $this->assertInstanceOf('Lunr\Gravity\SQLDMLQueryBuilder', $return);
        $this->assertSame($this->class, $return);
    }

    /**
     * Test specifying the JOIN part of a query.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsJoinTest::testJoinWithoutIndexHints
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::join
     */
    public function testJoinWithDefaultJoinType(): void
    {
        $this->class->join('table');
        $value = $this->getReflectionPropertyValue('join');

        $this->assertEquals('INNER JOIN table', $value);
    }

    /**
     * Test specifying the JOIN part of a query.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsJoinTest::testJoinWithoutIndexHints
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::join
     */
    public function testJoinWithNonDefaultJoinType(): void
    {
        $this->class->join('table', 'STRAIGHT');
        $value = $this->getReflectionPropertyValue('join');

        $this->assertEquals('STRAIGHT_JOIN table', $value);
    }

    /**
     * Test specifying the JOIN part of a query.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsJoinTest::testJoinWithoutIndexHints
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::join
     */
    public function testJoinWithoutIndexHints(): void
    {
        $this->class->join('table', 'STRAIGHT');
        $value = $this->getReflectionPropertyValue('join');

        $this->assertEquals('STRAIGHT_JOIN table', $value);
    }

    /**
     * Test specifying the JOIN part of a query.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsJoinTest::testJoinWithSingleIndexHint
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsJoinTest::testJoinWithMultipleIndexHints
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::join
     */
    public function testJoinWithIndexHints(): void
    {
        $hints = [ 'index_hint' ];
        $this->class->join('table', 'STRAIGHT', $hints);
        $value = $this->getReflectionPropertyValue('join');

        $this->assertEquals('STRAIGHT_JOIN table index_hint', $value);
    }

    /**
     * Test fluid interface of the join method.
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::join
     */
    public function testJoinReturnsSelfReference(): void
    {
        $return = $this->class->join('table');

        $this->assertInstanceOf('Lunr\Gravity\SQLDMLQueryBuilder', $return);
        $this->assertSame($this->class, $return);
    }

    /**
     * Test specifying a UNION statement.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsTest::testCompoundQuery
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::union
     */
    public function testUnion(): void
    {
        $this->class->union('QUERY');

        $this->assertPropertyEquals('compound', 'UNION QUERY');
    }

    /**
     * Test specifying a UNION DISTINCT statement.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsTest::testCompoundQuery
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::union
     */
    public function testUnionDistinct(): void
    {
        $this->class->union('QUERY', 'DISTINCT');

        $this->assertPropertyEquals('compound', 'UNION DISTINCT QUERY');
    }

    /**
     * Test specifying a UNION ALL statement.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsTest::testCompoundQuery
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::union
     */
    public function testUnionAll(): void
    {
        $this->class->union('QUERY', 'ALL');

        $this->assertPropertyEquals('compound', 'UNION ALL QUERY');
    }

    /**
     * Test fluid interface of the union method.
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::union
     */
    public function testUnionReturnsSelfReference(): void
    {
        $return = $this->class->union('QUERY');

        $this->assertInstanceOf('Lunr\Gravity\SQLDMLQueryBuilder', $return);
        $this->assertSame($this->class, $return);
    }

}

?>
