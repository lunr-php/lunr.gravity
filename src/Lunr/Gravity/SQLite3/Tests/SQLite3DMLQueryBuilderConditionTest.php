<?php

/**
 * This file contains the SQLite3DMLQueryBuilderConditionTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2013 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\SQLite3\Tests;

/**
 * This class contains the tests for the query parts necessary to build
 * where/having statements.
 *
 * @covers Lunr\Gravity\SQLite3\SQLite3DMLQueryBuilder
 */
class SQLite3DMLQueryBuilderConditionTest extends SQLite3DMLQueryBuilderTestCase
{

    /**
     * Test specifying the on part of a query.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionCreatesSimpleStatement
     * @covers  Lunr\Gravity\SQLite3\SQLite3DMLQueryBuilder::on_regexp
     */
    public function testOnRegexp(): void
    {
        $this->class->on_regexp('left', 'right');
        $this->assertPropertyEquals('join', 'ON left REGEXP right');
    }

    /**
     * Test specifying the on part of a query with non default operator.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionWithNonDefaultOperator
     * @covers  Lunr\Gravity\SQLite3\SQLite3DMLQueryBuilder::on_regexp
     */
    public function testOnNotRegexp(): void
    {
        $this->class->on_regexp('left', 'right', TRUE);
        $this->assertPropertyEquals('join', 'ON left NOT REGEXP right');
    }

    /**
     * Test fluid interface of the on_regexp method.
     *
     * @covers Lunr\Gravity\SQLite3\SQLite3DMLQueryBuilder::on_regexp
     */
    public function testOnRegexpReturnsSelfReference(): void
    {
        $return = $this->class->on_regexp('left', 'right');

        $this->assertInstanceOf('Lunr\Gravity\SQLite3\SQLite3DMLQueryBuilder', $return);
        $this->assertSame($this->class, $return);
    }

    /**
     * Test specifying the where part of a query.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionCreatesSimpleStatement
     * @covers  Lunr\Gravity\SQLite3\SQLite3DMLQueryBuilder::where_regexp
     */
    public function testWhereRegexp(): void
    {
        $this->class->where_regexp('left', 'right');
        $this->assertPropertyEquals('where', 'WHERE left REGEXP right');
    }

    /**
     * Test specifying the where part of a query with non default operator.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionWithNonDefaultOperator
     * @covers  Lunr\Gravity\SQLite3\SQLite3DMLQueryBuilder::where_regexp
     */
    public function testWhereNotRegexp(): void
    {
        $this->class->where_regexp('left', 'right', TRUE);
        $this->assertPropertyEquals('where', 'WHERE left NOT REGEXP right');
    }

    /**
     * Test fluid interface of the where_regexp method.
     *
     * @covers Lunr\Gravity\SQLite3\SQLite3DMLQueryBuilder::where_regexp
     */
    public function testWhereRegexpReturnsSelfReference(): void
    {
        $return = $this->class->where_regexp('left', 'right');

        $this->assertInstanceOf('Lunr\Gravity\SQLite3\SQLite3DMLQueryBuilder', $return);
        $this->assertSame($this->class, $return);
    }

    /**
     * Test specifying the having part of a query.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionCreatesSimpleStatement
     * @covers  Lunr\Gravity\SQLite3\SQLite3DMLQueryBuilder::having_regexp
     */
    public function testHavingRegexp(): void
    {
        $this->class->having_regexp('left', 'right');
        $this->assertPropertyEquals('having', 'HAVING left REGEXP right');
    }

    /**
     * Test specifying the having part of a query with non default operator.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionWithNonDefaultOperator
     * @covers  Lunr\Gravity\SQLite3\SQLite3DMLQueryBuilder::having_regexp
     */
    public function testHavingNotRegexp(): void
    {
        $this->class->having_regexp('left', 'right', TRUE);
        $this->assertPropertyEquals('having', 'HAVING left NOT REGEXP right');
    }

    /**
     * Test fluid interface of the having_regexp method.
     *
     * @covers Lunr\Gravity\SQLite3\SQLite3DMLQueryBuilder::having_regexp
     */
    public function testHavingRegexpReturnsSelfReference(): void
    {
        $return = $this->class->having_regexp('left', 'right');

        $this->assertInstanceOf('Lunr\Gravity\SQLite3\SQLite3DMLQueryBuilder', $return);
        $this->assertSame($this->class, $return);
    }

}

?>
