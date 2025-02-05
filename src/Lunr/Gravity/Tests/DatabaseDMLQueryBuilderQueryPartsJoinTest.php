<?php

/**
 * This file contains the DatabaseDMLQueryBuilderQueryPartsJoinTest class.
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
class DatabaseDMLQueryBuilderQueryPartsJoinTest extends DatabaseDMLQueryBuilderTest
{

    /**
     * Test specifying the JOIN part of a query.
     *
     * @param string $type Type of join to perform
     * @param string $join The join operation to perform
     *
     * @dataProvider commonJoinTypeProvider
     * @covers       Lunr\Gravity\DatabaseDMLQueryBuilder::sql_join
     */
    public function testJoinWithoutIndexHints($type, $join): void
    {
        $method = $this->getReflectionMethod('sql_join');

        $method->invokeArgs($this->class, [ 'table', $type ]);

        $string = trim($join . ' table');

        $this->assertEquals($string, $this->getReflectionPropertyValue('join'));
    }

    /**
     * Test specifying the JOIN part of a query.
     *
     * @param string $type Type of join to perform
     * @param string $join The join operation to perform
     *
     * @dataProvider commonJoinTypeProvider
     * @depends      Lunr\Gravity\Tests\DatabaseDMLQueryBuilderBaseTest::testPrepareValidIndexHints
     * @depends      Lunr\Gravity\Tests\DatabaseDMLQueryBuilderBaseTest::testPrepareInvalidIndexHintsReturnsEmptyString
     * @covers       Lunr\Gravity\DatabaseDMLQueryBuilder::sql_join
     */
    public function testJoinWithSingleIndexHint($type, $join): void
    {
        $method = $this->getReflectionMethod('sql_join');

        $hints = [ 'index_hint' ];

        $method->invokeArgs($this->class, [ 'table', $type, $hints ]);

        $string = trim($join . ' table index_hint');

        $this->assertEquals($string, $this->getReflectionPropertyValue('join'));
    }

    /**
     * Test specifying the JOIN part of a query.
     *
     * @param string $type Type of join to perform
     * @param string $join The join operation to perform
     *
     * @dataProvider commonJoinTypeProvider
     * @depends      Lunr\Gravity\Tests\DatabaseDMLQueryBuilderBaseTest::testPrepareValidIndexHints
     * @depends      Lunr\Gravity\Tests\DatabaseDMLQueryBuilderBaseTest::testPrepareInvalidIndexHintsReturnsEmptyString
     * @covers       Lunr\Gravity\DatabaseDMLQueryBuilder::sql_join
     */
    public function testJoinWithMultipleIndexHints($type, $join): void
    {
        $method = $this->getReflectionMethod('sql_join');
        $hints  = [ 'index_hint', 'index_hint' ];

        $method->invokeArgs($this->class, [ 'table', $type, $hints ]);

        $string = trim($join . ' table index_hint, index_hint');

        $this->assertEquals($string, $this->getReflectionPropertyValue('join'));
    }

    /**
     * Test specifying the JOIN part of a query.
     *
     * @param string $type Type of join to perform
     * @param string $join The join operation to perform
     *
     * @dataProvider commonJoinTypeProvider
     * @depends      Lunr\Gravity\Tests\DatabaseDMLQueryBuilderBaseTest::testPrepareValidIndexHints
     * @depends      Lunr\Gravity\Tests\DatabaseDMLQueryBuilderBaseTest::testPrepareInvalidIndexHintsReturnsEmptyString
     * @covers       Lunr\Gravity\DatabaseDMLQueryBuilder::sql_join
     */
    public function testJoinWithNULLIndexHints($type, $join): void
    {
        $method = $this->getReflectionMethod('sql_join');
        $hints  = [ NULL, NULL ];

        $method->invokeArgs($this->class, [ 'table', $type, $hints ]);

        $string = ltrim($join . ' table ');

        $this->assertEquals($string, $this->getReflectionPropertyValue('join'));
    }

    /**
     * Test specifying the JOIN part of a query.
     *
     * @param string $type Type of join to perform
     * @param string $join The join operation to perform
     *
     * @dataProvider commonJoinTypeProvider
     * @covers       Lunr\Gravity\DatabaseDMLQueryBuilder::sql_join
     */
    public function testIncrementalJoinWithoutIndexes($type, $join): void
    {
        $method = $this->getReflectionMethod('sql_join');

        $method->invokeArgs($this->class, [ 'table', $type ]);
        $method->invokeArgs($this->class, [ 'table', $type ]);

        $string = $join . ' table ' . $join . ' table';

        $this->assertEquals($string, $this->getReflectionPropertyValue('join'));
    }

    /**
     * Test specifying the JOIN part of a query.
     *
     * @param string $type Type of join to perform
     * @param string $join The join operation to perform
     *
     * @dataProvider commonJoinTypeProvider
     * @depends      Lunr\Gravity\Tests\DatabaseDMLQueryBuilderBaseTest::testPrepareValidIndexHints
     * @depends      Lunr\Gravity\Tests\DatabaseDMLQueryBuilderBaseTest::testPrepareInvalidIndexHintsReturnsEmptyString
     * @covers       Lunr\Gravity\DatabaseDMLQueryBuilder::sql_join
     */
    public function testIncrementalJoinWithIndexes($type, $join): void
    {
        $method = $this->getReflectionMethod('sql_join');
        $hints  = [ 'index_hint' ];

        $method->invokeArgs($this->class, [ 'table', $type, $hints ]);
        $method->invokeArgs($this->class, [ 'table', $type, $hints ]);

        $string = $join . ' table index_hint ' . $join . ' table index_hint';

        $this->assertEquals($string, $this->getReflectionPropertyValue('join'));
    }

    /**
     * Test specifying the JOIN part of a query with a STRAIGHT type.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_join
     */
    public function testStraightJoin(): void
    {
        $method = $this->getReflectionMethod('sql_join');

        $method->invokeArgs($this->class, [ 'table', 'STRAIGHT' ]);

        $string = 'STRAIGHT_JOIN table';

        $this->assertEquals($string, $this->getReflectionPropertyValue('join'));
    }

    /**
     * Test specifying the JOIN part of a query with a STRAIGHT type.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_join
     */
    public function testIncrementalStraightJoin(): void
    {
        $method = $this->getReflectionMethod('sql_join');

        $method->invokeArgs($this->class, [ 'table', 'STRAIGHT' ]);
        $method->invokeArgs($this->class, [ 'table', 'STRAIGHT' ]);

        $string = 'STRAIGHT_JOIN table STRAIGHT_JOIN table';

        $this->assertEquals($string, $this->getReflectionPropertyValue('join'));
    }

    /**
     * Test that specifying a join clause sets the property is_unfinished_join to FALSE
     * when there is a natural join.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_join
     */
    public function testJoinSetsUnfinishedJoinWithNaturalJoin(): void
    {
        $method = $this->getReflectionMethod('sql_join');

        $method->invokeArgs($this->class, [ 'table', 'NATURAL LEFT JOIN' ]);

        $this->assertFalse($this->getReflectionPropertyValue('is_unfinished_join'));
    }

    /**
     * Test that specifying a join clause sets the property is_unfinished_join to TRUE
     * when the join still has to be finished.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_join
     */
    public function testJoinSetsUnfinishedJoin(): void
    {
        $method = $this->getReflectionMethod('sql_join');

        $method->invokeArgs($this->class, [ 'table', 'INNER' ]);

        $this->assertTrue($this->getReflectionPropertyValue('is_unfinished_join'));
    }

    /**
     * Test that specifying a join clause resets the property join_type to ' ' after having used join before.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_join
     */
    public function testJoinSetsJoinType(): void
    {
        $this->setReflectionPropertyValue('join_type', 'on');

        $method = $this->getReflectionMethod('sql_join');

        $method->invokeArgs($this->class, [ 'table', 'INNER' ]);

        $this->assertTrue($this->getReflectionPropertyValue('is_unfinished_join'));
        $this->assertSame('', $this->getReflectionPropertyValue('join_type'));
    }

}

?>
