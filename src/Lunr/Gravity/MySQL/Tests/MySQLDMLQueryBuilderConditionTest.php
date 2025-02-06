<?php

/**
 * This file contains the MySQLDMLQueryBuilderConditionTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2012 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MySQL\Tests;

/**
 * This class contains the tests for the query parts necessary to build
 * where/having statements.
 *
 * @covers Lunr\Gravity\MySQL\MySQLDMLQueryBuilder
 */
class MySQLDMLQueryBuilderConditionTest extends MySQLDMLQueryBuilderTestCase
{

    /**
     * Test specifying the on part of a query.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionCreatesSimpleStatement
     * @covers  Lunr\Gravity\MySQL\MySQLDMLQueryBuilder::on_regexp
     */
    public function testOnRegexp(): void
    {
        $property = $this->builder_reflection->getProperty('join');
        $property->setAccessible(TRUE);

        $this->builder->on_regexp('left', 'right');

        $this->assertEquals('ON left REGEXP right', $property->getValue($this->builder));
    }

    /**
     * Test specifying the on part of a query with non default operator.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionWithNonDefaultOperator
     * @covers  Lunr\Gravity\MySQL\MySQLDMLQueryBuilder::on_regexp
     */
    public function testOnNotRegexp(): void
    {
        $property = $this->builder_reflection->getProperty('join');
        $property->setAccessible(TRUE);

        $this->builder->on_regexp('left', 'right', TRUE);

        $this->assertEquals('ON left NOT REGEXP right', $property->getValue($this->builder));
    }

    /**
     * Test fluid interface of the on_regexp method.
     *
     * @covers Lunr\Gravity\MySQL\MySQLDMLQueryBuilder::on_regexp
     */
    public function testOnRegexpReturnsSelfReference(): void
    {
        $return = $this->builder->on_regexp('left', 'right');

        $this->assertInstanceOf('Lunr\Gravity\MySQL\MySQLDMLQueryBuilder', $return);
        $this->assertSame($this->builder, $return);
    }

    /**
     * Test specifying the where part of a query.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionCreatesSimpleStatement
     * @covers  Lunr\Gravity\MySQL\MySQLDMLQueryBuilder::where_regexp
     */
    public function testWhereRegexp(): void
    {
        $property = $this->builder_reflection->getProperty('where');
        $property->setAccessible(TRUE);

        $this->builder->where_regexp('left', 'right');

        $this->assertEquals('WHERE left REGEXP right', $property->getValue($this->builder));
    }

    /**
     * Test specifying the where part of a query with non default operator.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionWithNonDefaultOperator
     * @covers  Lunr\Gravity\MySQL\MySQLDMLQueryBuilder::where_regexp
     */
    public function testWhereNotRegexp(): void
    {
        $property = $this->builder_reflection->getProperty('where');
        $property->setAccessible(TRUE);

        $this->builder->where_regexp('left', 'right', TRUE);

        $this->assertEquals('WHERE left NOT REGEXP right', $property->getValue($this->builder));
    }

    /**
     * Test fluid interface of the where_regexp method.
     *
     * @covers Lunr\Gravity\MySQL\MySQLDMLQueryBuilder::where_regexp
     */
    public function testWhereRegexpReturnsSelfReference(): void
    {
        $return = $this->builder->where_regexp('left', 'right');

        $this->assertInstanceOf('Lunr\Gravity\MySQL\MySQLDMLQueryBuilder', $return);
        $this->assertSame($this->builder, $return);
    }

    /**
     * Test specifying the having part of a query.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionCreatesSimpleStatement
     * @covers  Lunr\Gravity\MySQL\MySQLDMLQueryBuilder::having_regexp
     */
    public function testHavingRegexp(): void
    {
        $property = $this->builder_reflection->getProperty('having');
        $property->setAccessible(TRUE);

        $this->builder->having_regexp('left', 'right');

        $this->assertEquals('HAVING left REGEXP right', $property->getValue($this->builder));
    }

    /**
     * Test specifying the having part of a query with non default operator.
     *
     * @depends Lunr\Gravity\Tests\DatabaseDMLQueryBuilderQueryPartsConditionTest::testConditionWithNonDefaultOperator
     * @covers  Lunr\Gravity\MySQL\MySQLDMLQueryBuilder::having_regexp
     */
    public function testHavingNotRegexp(): void
    {
        $property = $this->builder_reflection->getProperty('having');
        $property->setAccessible(TRUE);

        $this->builder->having_regexp('left', 'right', TRUE);

        $this->assertEquals('HAVING left NOT REGEXP right', $property->getValue($this->builder));
    }

    /**
     * Test fluid interface of the having_regexp method.
     *
     * @covers Lunr\Gravity\MySQL\MySQLDMLQueryBuilder::having_regexp
     */
    public function testHavingRegexpReturnsSelfReference(): void
    {
        $return = $this->builder->having_regexp('left', 'right');

        $this->assertInstanceOf('Lunr\Gravity\MySQL\MySQLDMLQueryBuilder', $return);
        $this->assertSame($this->builder, $return);
    }

    /**
     * Test specifying a logical XOR connector.
     *
     * @covers Lunr\Gravity\MySQL\MySQLDMLQueryBuilder::sql_xor
     */
    public function testSQLXor(): void
    {
        $property = $this->builder_reflection->getProperty('connector');
        $property->setAccessible(TRUE);

        $this->builder->sql_xor();

        $this->assertEquals('XOR', $property->getValue($this->builder));
    }

    /**
     * Test fluid interface of the sql_xor method.
     *
     * @covers Lunr\Gravity\MySQL\MySQLDMLQueryBuilder::sql_xor
     */
    public function testSQLXorReturnsSelfReference(): void
    {
        $return = $this->builder->sql_xor();

        $this->assertInstanceOf('Lunr\Gravity\MySQL\MySQLDMLQueryBuilder', $return);
        $this->assertSame($this->builder, $return);
    }

    /**
     * Test specifying a logical XOR connector.
     *
     * @covers Lunr\Gravity\MySQL\MySQLDMLQueryBuilder::xor
     */
    public function testXor(): void
    {
        $property = $this->builder_reflection->getProperty('connector');
        $property->setAccessible(TRUE);

        $this->builder->xor();

        $this->assertEquals('XOR', $property->getValue($this->builder));
    }

    /**
     * Test fluid interface of the sql_xor method.
     *
     * @covers Lunr\Gravity\MySQL\MySQLDMLQueryBuilder::xor
     */
    public function testXorReturnsSelfReference(): void
    {
        $return = $this->builder->xor();

        $this->assertInstanceOf('Lunr\Gravity\MySQL\MySQLDMLQueryBuilder', $return);
        $this->assertSame($this->builder, $return);
    }

}

?>
