<?php

/**
 * This file contains the SQLDMLQueryBuilderConditionTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2013 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\Tests;

/**
 * This class contains the tests for the query parts necessary to build
 * where/having statements.
 *
 * @covers Lunr\Gravity\SQLDMLQueryBuilder
 */
class SQLDMLQueryBuilderConditionTest extends SQLDMLQueryBuilderTestCase
{

    /**
     * Test grouping of ON condition (start group).
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::start_on_group
     */
    public function testOpeningGroupOn(): void
    {
        $this->class->start_on_group();
        $this->assertPropertyEquals('join', '(');
    }

    /**
     * Test grouping of ON condition (close group).
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::end_on_group
     */
    public function testClosingGroupOn(): void
    {
        $this->class->end_on_group();
        $this->assertPropertyEquals('join', ')');
    }

    /**
     * Test specifying the on part of a query.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionCreatesSimpleStatement
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::on
     */
    public function testOnWithDefaultOperator(): void
    {
        $this->class->on('left', 'right');
        $this->assertPropertyEquals('join', 'ON left = right');
    }

    /**
     * Test specifying the on part of a query with non default operator.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionWithNonDefaultOperator
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::on
     */
    public function testOnWithCustomOperator(): void
    {
        $this->class->on('left', 'right', '>');
        $this->assertPropertyEquals('join', 'ON left > right');
    }

    /**
     * Test fluid interface of the on method.
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::on
     */
    public function testOnReturnsSelfReference(): void
    {
        $return = $this->class->on('left', 'right');

        $this->assertInstanceOf('Lunr\Gravity\SQLDMLQueryBuilder', $return);
        $this->assertSame($this->class, $return);
    }

    /**
     * Test specifying the on part of a query.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionCreatesSimpleStatement
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::on_like
     */
    public function testOnLike(): void
    {
        $this->class->on_like('left', 'right');
        $this->assertPropertyEquals('join', 'ON left LIKE right');
    }

    /**
     * Test specifying the on part of a query with non default operator.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionWithNonDefaultOperator
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::on_like
     */
    public function testOnNotLike(): void
    {
        $this->class->on_like('left', 'right', TRUE);
        $this->assertPropertyEquals('join', 'ON left NOT LIKE right');
    }

    /**
     * Test fluid interface of the on_like method.
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::on_like
     */
    public function testOnLikeReturnsSelfReference(): void
    {
        $return = $this->class->on_like('left', 'right');

        $this->assertInstanceOf('Lunr\Gravity\SQLDMLQueryBuilder', $return);
        $this->assertSame($this->class, $return);
    }

    /**
     * Test specifying the on part of a query.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionCreatesSimpleStatement
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::on_in
     */
    public function testOnIn(): void
    {
        $this->class->on_in('left', 'right');
        $this->assertPropertyEquals('join', 'ON left IN right');
    }

    /**
     * Test specifying the on part of a query with non default operator.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionWithNonDefaultOperator
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::on_in
     */
    public function testOnNotIn(): void
    {
        $this->class->on_in('left', 'right', TRUE);
        $this->assertPropertyEquals('join', 'ON left NOT IN right');
    }

    /**
     * Test fluid interface of the on_in method.
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::on_in
     */
    public function testOnInReturnsSelfReference(): void
    {
        $return = $this->class->on_in('left', 'right');

        $this->assertInstanceOf('Lunr\Gravity\SQLDMLQueryBuilder', $return);
        $this->assertSame($this->class, $return);
    }

    /**
     * Test specifying the on part of a query.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionCreatesSimpleStatement
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::on_between
     */
    public function testOnBetween(): void
    {
        $this->class->on_between('left', 'lower', 'upper');
        $this->assertPropertyEquals('join', 'ON left BETWEEN lower AND upper');
    }

    /**
     * Test specifying the on part of a query with non default operator.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionWithNonDefaultOperator
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::on_between
     */
    public function testOnNotBetween(): void
    {
        $this->class->on_between('left', 'lower', 'upper', TRUE);
        $this->assertPropertyEquals('join', 'ON left NOT BETWEEN lower AND upper');
    }

    /**
     * Test fluid interface of the on_between method.
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::on_between
     */
    public function testOnBetweenReturnsSelfReference(): void
    {
        $return = $this->class->on_between('left', 'lower', 'upper');

        $this->assertInstanceOf('Lunr\Gravity\SQLDMLQueryBuilder', $return);
        $this->assertSame($this->class, $return);
    }

    /**
     * Test specifying the on part of a query.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionCreatesSimpleStatement
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::on_null
     */
    public function testOnNull(): void
    {
        $this->class->on_null('left');
        $this->assertPropertyEquals('join', 'ON left IS NULL');
    }

    /**
     * Test specifying the on part of a query with non default operator.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionWithNonDefaultOperator
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::on_null
     */
    public function testOnNotNull(): void
    {
        $this->class->on_null('left', TRUE);
        $this->assertPropertyEquals('join', 'ON left IS NOT NULL');
    }

    /**
     * Test fluid interface of the on_null method.
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::on_null
     */
    public function testOnNullReturnsSelfReference(): void
    {
        $return = $this->class->on_null('left');

        $this->assertInstanceOf('Lunr\Gravity\SQLDMLQueryBuilder', $return);
        $this->assertSame($this->class, $return);
    }

    /**
     * Test grouping of WHERE condition (start group).
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::start_where_group
     */
    public function testOpeningGroupWhere(): void
    {
        $this->class->start_where_group();
        $this->assertPropertyEquals('where', '(');
    }

    /**
     * Test grouping of WHERE condition (close group).
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::end_where_group
     */
    public function testClosingGroupWhere(): void
    {
        $this->class->end_where_group();
        $this->assertPropertyEquals('where', ')');
    }

    /**
     * Test specifying the where part of a query.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionCreatesSimpleStatement
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::where
     */
    public function testWhereWithDefaultOperator(): void
    {
        $this->class->where('left', 'right');
        $this->assertPropertyEquals('where', 'WHERE left = right');
    }

    /**
     * Test specifying the where part of a query with non default operator.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionWithNonDefaultOperator
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::where
     */
    public function testWhereWithCustomOperator(): void
    {
        $this->class->where('left', 'right', '>');
        $this->assertPropertyEquals('where', 'WHERE left > right');
    }

    /**
     * Test fluid interface of the where method.
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::where
     */
    public function testWhereReturnsSelfReference(): void
    {
        $return = $this->class->where('left', 'right');

        $this->assertInstanceOf('Lunr\Gravity\SQLDMLQueryBuilder', $return);
        $this->assertSame($this->class, $return);
    }

    /**
     * Test specifying the where part of a query.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionCreatesSimpleStatement
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::where_like
     */
    public function testWhereLike(): void
    {
        $this->class->where_like('left', 'right');
        $this->assertPropertyEquals('where', 'WHERE left LIKE right');
    }

    /**
     * Test specifying the where part of a query with non default operator.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionWithNonDefaultOperator
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::where_like
     */
    public function testWhereNotLike(): void
    {
        $this->class->where_like('left', 'right', TRUE);
        $this->assertPropertyEquals('where', 'WHERE left NOT LIKE right');
    }

    /**
     * Test fluid interface of the where_like method.
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::where_like
     */
    public function testWhereLikeReturnsSelfReference(): void
    {
        $return = $this->class->where_like('left', 'right');

        $this->assertInstanceOf('Lunr\Gravity\SQLDMLQueryBuilder', $return);
        $this->assertSame($this->class, $return);
    }

    /**
     * Test specifying the where part of a query.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionCreatesSimpleStatement
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::where_in
     */
    public function testWhereIn(): void
    {
        $this->class->where_in('left', 'right');
        $this->assertPropertyEquals('where', 'WHERE left IN right');
    }

    /**
     * Test specifying the where part of a query with non default operator.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionWithNonDefaultOperator
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::where_in
     */
    public function testWhereNotIn(): void
    {
        $this->class->where_in('left', 'right', TRUE);
        $this->assertPropertyEquals('where', 'WHERE left NOT IN right');
    }

    /**
     * Test fluid interface of the where_in method.
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::where_in
     */
    public function testWhereInReturnsSelfReference(): void
    {
        $return = $this->class->where_in('left', 'right');

        $this->assertInstanceOf('Lunr\Gravity\SQLDMLQueryBuilder', $return);
        $this->assertSame($this->class, $return);
    }

    /**
     * Test specifying the where part of a query.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionCreatesSimpleStatement
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::where_between
     */
    public function testWhereBetween(): void
    {
        $this->class->where_between('left', 'lower', 'upper');
        $this->assertPropertyEquals('where', 'WHERE left BETWEEN lower AND upper');
    }

    /**
     * Test specifying the where part of a query with non default operator.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionWithNonDefaultOperator
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::where_between
     */
    public function testWhereNotBetween(): void
    {
        $this->class->where_between('left', 'lower', 'upper', TRUE);
        $this->assertPropertyEquals('where', 'WHERE left NOT BETWEEN lower AND upper');
    }

    /**
     * Test fluid interface of the where_between method.
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::where_between
     */
    public function testWhereBetweenReturnsSelfReference(): void
    {
        $return = $this->class->where_between('left', 'lower', 'upper');

        $this->assertInstanceOf('Lunr\Gravity\SQLDMLQueryBuilder', $return);
        $this->assertSame($this->class, $return);
    }

    /**
     * Test specifying the where part of a query.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionCreatesSimpleStatement
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::where_null
     */
    public function testWhereNull(): void
    {
        $this->class->where_null('left');
        $this->assertPropertyEquals('where', 'WHERE left IS NULL');
    }

    /**
     * Test specifying the where part of a query with non default operator.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionWithNonDefaultOperator
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::where_null
     */
    public function testWhereNotNull(): void
    {
        $this->class->where_null('left', TRUE);
        $this->assertPropertyEquals('where', 'WHERE left IS NOT NULL');
    }

    /**
     * Test fluid interface of the where_null method.
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::where_null
     */
    public function testWhereNullReturnsSelfReference(): void
    {
        $return = $this->class->where_null('left');

        $this->assertInstanceOf('Lunr\Gravity\SQLDMLQueryBuilder', $return);
        $this->assertSame($this->class, $return);
    }

    /**
     * Test grouping of HAVING condition (start group).
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::start_having_group
     */
    public function testOpeningGroupHaving(): void
    {
        $this->class->start_having_group();
        $this->assertPropertyEquals('having', '(');
    }

    /**
     * Test grouping of HAVING condition (close group).
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::end_having_group
     */
    public function testClosingGroupHaving(): void
    {
        $this->class->end_having_group();
        $this->assertPropertyEquals('having', ')');
    }

    /**
     * Test specifying the having part of a query.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionCreatesSimpleStatement
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::having
     */
    public function testHavingWithDefaultOperator(): void
    {
        $this->class->having('left', 'right');
        $this->assertPropertyEquals('having', 'HAVING left = right');
    }

    /**
     * Test specifying the having part of a query with non default operator.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionWithNonDefaultOperator
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::having
     */
    public function testHavingWithCustomOperator(): void
    {
        $this->class->having('left', 'right', '>');
        $this->assertPropertyEquals('having', 'HAVING left > right');
    }

    /**
     * Test fluid interface of the having method.
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::having
     */
    public function testHavingReturnsSelfReference(): void
    {
        $return = $this->class->having('left', 'right');

        $this->assertInstanceOf('Lunr\Gravity\SQLDMLQueryBuilder', $return);
        $this->assertSame($this->class, $return);
    }

    /**
     * Test specifying the having part of a query.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionCreatesSimpleStatement
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::having_like
     */
    public function testHavingLike(): void
    {
        $this->class->having_like('left', 'right');
        $this->assertPropertyEquals('having', 'HAVING left LIKE right');
    }

    /**
     * Test specifying the having part of a query with non default operator.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionWithNonDefaultOperator
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::having_like
     */
    public function testHavingNotLike(): void
    {
        $this->class->having_like('left', 'right', TRUE);
        $this->assertPropertyEquals('having', 'HAVING left NOT LIKE right');
    }

    /**
     * Test fluid interface of the having_like method.
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::having_like
     */
    public function testHavingLikeReturnsSelfReference(): void
    {
        $return = $this->class->having_like('left', 'right');

        $this->assertInstanceOf('Lunr\Gravity\SQLDMLQueryBuilder', $return);
        $this->assertSame($this->class, $return);
    }

    /**
     * Test specifying the having part of a query.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionCreatesSimpleStatement
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::having_in
     */
    public function testHavingIn(): void
    {
        $this->class->having_in('left', 'right');
        $this->assertPropertyEquals('having', 'HAVING left IN right');
    }

    /**
     * Test specifying the having part of a query with non default operator.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionWithNonDefaultOperator
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::having_in
     */
    public function testHavingNotIn(): void
    {
        $this->class->having_in('left', 'right', TRUE);
        $this->assertPropertyEquals('having', 'HAVING left NOT IN right');
    }

    /**
     * Test fluid interface of the having_in method.
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::having_in
     */
    public function testHavingInReturnsSelfReference(): void
    {
        $return = $this->class->having_in('left', 'right');

        $this->assertInstanceOf('Lunr\Gravity\SQLDMLQueryBuilder', $return);
        $this->assertSame($this->class, $return);
    }

    /**
     * Test specifying the having part of a query.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionCreatesSimpleStatement
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::having_between
     */
    public function testHavingBetween(): void
    {
        $this->class->having_between('left', 'lower', 'upper');
        $this->assertPropertyEquals('having', 'HAVING left BETWEEN lower AND upper');
    }

    /**
     * Test specifying the having part of a query with non default operator.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionWithNonDefaultOperator
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::having_between
     */
    public function testHavingNotBetween(): void
    {
        $this->class->having_between('left', 'lower', 'upper', TRUE);
        $this->assertPropertyEquals('having', 'HAVING left NOT BETWEEN lower AND upper');
    }

    /**
     * Test fluid interface of the having_between method.
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::having_between
     */
    public function testHavingBetweenReturnsSelfReference(): void
    {
        $return = $this->class->having_between('left', 'lower', 'upper');

        $this->assertInstanceOf('Lunr\Gravity\SQLDMLQueryBuilder', $return);
        $this->assertSame($this->class, $return);
    }

    /**
     * Test specifying the having part of a query.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionCreatesSimpleStatement
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::having_null
     */
    public function testHavingNull(): void
    {
        $this->class->having_null('left');
        $this->assertPropertyEquals('having', 'HAVING left IS NULL');
    }

    /**
     * Test specifying the having part of a query with non default operator.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionWithNonDefaultOperator
     * @covers  Lunr\Gravity\SQLDMLQueryBuilder::having_null
     */
    public function testHavingNotNull(): void
    {
        $this->class->having_null('left', TRUE);
        $this->assertPropertyEquals('having', 'HAVING left IS NOT NULL');
    }

    /**
     * Test fluid interface of the having_null method.
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::having_null
     */
    public function testHavingNullReturnsSelfReference(): void
    {
        $return = $this->class->having_null('left');

        $this->assertInstanceOf('Lunr\Gravity\SQLDMLQueryBuilder', $return);
        $this->assertSame($this->class, $return);
    }

    /**
     * Test specifying a logical AND connector.
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::sql_and
     */
    public function testSQLAnd(): void
    {
        $this->class->sql_and();
        $this->assertPropertyEquals('connector', 'AND');
    }

    /**
     * Test fluid interface of the sql_and method.
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::sql_and
     */
    public function testSQLAndReturnsSelfReference(): void
    {
        $return = $this->class->sql_and();

        $this->assertInstanceOf('Lunr\Gravity\SQLDMLQueryBuilder', $return);
        $this->assertSame($this->class, $return);
    }

    /**
     * Test specifying a logical AND connector.
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::and
     */
    public function testAnd(): void
    {
        $this->class->and();
        $this->assertPropertyEquals('connector', 'AND');
    }

    /**
     * Test fluid interface of the sql_and method.
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::and
     */
    public function testAndReturnsSelfReference(): void
    {
        $return = $this->class->and();

        $this->assertInstanceOf('Lunr\Gravity\SQLDMLQueryBuilder', $return);
        $this->assertSame($this->class, $return);
    }

    /**
     * Test specifying a logical OR connector.
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::sql_or
     */
    public function testSQLOr(): void
    {
        $this->class->sql_or();
        $this->assertPropertyEquals('connector', 'OR');
    }

    /**
     * Test fluid interface of the sql_or method.
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::sql_or
     */
    public function testSQLOrReturnsSelfReference(): void
    {
        $return = $this->class->sql_or();

        $this->assertInstanceOf('Lunr\Gravity\SQLDMLQueryBuilder', $return);
        $this->assertSame($this->class, $return);
    }

    /**
     * Test specifying a logical OR connector.
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::or
     */
    public function testOr(): void
    {
        $this->class->or();
        $this->assertPropertyEquals('connector', 'OR');
    }

    /**
     * Test fluid interface of the sql_or method.
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::or
     */
    public function testOrReturnsSelfReference(): void
    {
        $return = $this->class->or();

        $this->assertInstanceOf('Lunr\Gravity\SQLDMLQueryBuilder', $return);
        $this->assertSame($this->class, $return);
    }

}

?>
