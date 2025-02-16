<?php

/**
 * This file contains the DatabaseDMLQueryBuilderQueryPartsConditionTest class.
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
class DatabaseDMLQueryBuilderQueryPartsConditionTest extends DatabaseDMLQueryBuilderTestCase
{

    /**
     * Test specifying a logical connector for the query.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_connector
     */
    public function testConnector(): void
    {
        $method = $this->getReflectionMethod('sql_connector');

        $method->invokeArgs($this->class, [ 'AND' ]);

        $this->assertEquals('AND', $this->getReflectionPropertyValue('connector'));
    }

    /**
     * Test creating a simple where/having statement.
     *
     * @param string $keyword   The expected statement keyword
     * @param string $attribute The name of the property where the statement is stored
     *
     * @dataProvider ConditionalKeywordProvider
     * @covers       Lunr\Gravity\DatabaseDMLQueryBuilder::sql_condition
     */
    public function testConditionCreatesSimpleStatement($keyword, $attribute): void
    {
        $method = $this->getReflectionMethod('sql_condition');

        $method->invokeArgs($this->class, [ 'a', 'b', '=', $keyword ]);

        $string = "$keyword a = b";

        $this->assertEquals($string, $this->getReflectionPropertyValue($attribute));
    }

    /**
     * Test creating a simple JOIN ON statement.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_condition
     */
    public function testConditionCreatesSimpleJoinStatement(): void
    {
        $method = $this->getReflectionMethod('sql_condition');

        $this->setReflectionPropertyValue('isUnfinishedJoin', TRUE);
        $this->setReflectionPropertyValue('join', 'JOIN table');

        $method->invokeArgs($this->class, [ 'a', 'b', '=', 'ON' ]);

        $string = 'JOIN table ON a = b';

        $this->assertEquals($string, $this->getReflectionPropertyValue('join'));
    }

    /**
     * Test creating a simple JOIN ON statement.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_condition
     */
    public function testConditionCreatesGroupedJoinStatement(): void
    {
        $method = $this->getReflectionMethod('sql_condition');

        $this->setReflectionPropertyValue('isUnfinishedJoin', FALSE);

        $this->setReflectionPropertyValue('join', 'JOIN table ON (');

        $method->invokeArgs($this->class, [ 'a', 'b', '=', 'ON' ]);

        $string = 'JOIN table ON (a = b';

        $this->assertEquals($string, $this->getReflectionPropertyValue('join'));
    }

    /**
     * Test creating a where/having statement with non-default operator.
     *
     * @param string $keyword   The expected statement keyword
     * @param string $attribute The name of the property where the statement is stored
     *
     * @dataProvider ConditionalKeywordProvider
     * @covers       Lunr\Gravity\DatabaseDMLQueryBuilder::sql_condition
     */
    public function testConditionWithNonDefaultOperator($keyword, $attribute): void
    {
        $method = $this->getReflectionMethod('sql_condition');

        $method->invokeArgs($this->class, [ 'a', 'b', '<', $keyword ]);

        $string = "$keyword a < b";

        $this->assertEquals($string, $this->getReflectionPropertyValue($attribute));
    }

    /**
     * Test extending a where/having statement with default connector.
     *
     * @param string $keyword   The expected statement keyword
     * @param string $attribute The name of the property where the statement is stored
     *
     * @dataProvider ConditionalKeywordProvider
     * @covers       Lunr\Gravity\DatabaseDMLQueryBuilder::sql_condition
     */
    public function testConditionExtendingWithDefaultConnector($keyword, $attribute): void
    {
        $string = "$keyword a = b";
        $method = $this->getReflectionMethod('sql_condition');

        $this->setReflectionPropertyValue($attribute, $string);

        $method->invokeArgs($this->class, [ 'c', 'd', '=', $keyword ]);

        $string = "$keyword a = b AND c = d";

        $this->assertEquals($string, $this->getReflectionPropertyValue($attribute));
    }

    /**
     * Test extending a where/having statement with a specified connector.
     *
     * @param string $keyword   The expected statement keyword
     * @param string $attribute The name of the property where the statement is stored
     *
     * @dataProvider ConditionalKeywordProvider
     * @covers       Lunr\Gravity\DatabaseDMLQueryBuilder::sql_condition
     */
    public function testConditionExtendingWithSpecifiedConnector($keyword, $attribute): void
    {
        $string = "$keyword a = b";

        $method = $this->getReflectionMethod('sql_condition');

        $this->setReflectionPropertyValue('connector', 'OR');
        $this->setReflectionPropertyValue($attribute, $string);

        $method->invokeArgs($this->class, [ 'c', 'd', '=', $keyword ]);

        $string = "$keyword a = b OR c = d";

        $this->assertEquals($string, $this->getReflectionPropertyValue($attribute));
    }

    /**
     * Test getting a select query with grouped condition.
     *
     * @param string $keyword   The expected statement keyword
     * @param string $attribute The name of the property where the statement is stored
     *
     * @dataProvider conditionalKeywordProvider
     * @covers       Lunr\Gravity\DatabaseDMLQueryBuilder::sql_condition
     */
    public function testGroupedSQLCondition($keyword, $attribute): void
    {
        $methodCond = $this->getReflectionMethod('sql_condition');

        $arguments = [ 'a', 'b', '=', $keyword ];

        $this->setReflectionPropertyValue($attribute, '(');

        $string = $keyword . ' (a = b';
        $methodCond->invokeArgs($this->class, $arguments);
        $this->assertEquals($string, $this->getReflectionPropertyValue($attribute));
    }

    /**
     * Test if 'where' works after 'using'.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_condition
     */
    public function testWhereWithJoinTypeUsing(): void
    {
        $this->setReflectionPropertyValue('join', 'INNER JOIN `table1` USING (column3)');
        $this->setReflectionPropertyValue('isUnfinishedJoin', FALSE);
        $this->setReflectionPropertyValue('joinType', 'using');

        $method = $this->getReflectionMethod('sql_condition');

        $method->invokeArgs($this->class, [ 'column4', 'column5', '=', 'WHERE' ]);

        $this->assertSame('INNER JOIN `table1` USING (column3)', $this->getReflectionPropertyValue('join'));
        $this->assertSame('WHERE column4 = column5', $this->getReflectionPropertyValue('where'));
    }

    /**
     * Test if 'where' works after 'on'.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_condition
     */
    public function testWhereWithJoinTypeOn(): void
    {
        $this->setReflectionPropertyValue('join', 'INNER JOIN `table1`ON (`column2` = `column3`)');
        $this->setReflectionPropertyValue('isUnfinishedJoin', FALSE);
        $this->setReflectionPropertyValue('joinType', 'on');

        $method = $this->getReflectionMethod('sql_condition');

        $method->invokeArgs($this->class, [ 'column4', 'column5', '=', 'WHERE' ]);

        $this->assertSame('INNER JOIN `table1`ON (`column2` = `column3`)', $this->getReflectionPropertyValue('join'));
        $this->assertSame('WHERE column4 = column5', $this->getReflectionPropertyValue('where'));
    }

    /**
     * Test if 'where' works with join type empty.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_condition
     */
    public function testWhereWithJoinTypeEmpty(): void
    {
        $this->setReflectionPropertyValue('join', 'INNER JOIN `table1`ON (`column2` = `column3`)');
        $this->setReflectionPropertyValue('isUnfinishedJoin', FALSE);
        $this->setReflectionPropertyValue('joinType', '');

        $method = $this->getReflectionMethod('sql_condition');

        $method->invokeArgs($this->class, [ 'column4', 'column5', '=', 'WHERE' ]);

        $this->assertSame('INNER JOIN `table1`ON (`column2` = `column3`)', $this->getReflectionPropertyValue('join'));
        $this->assertSame('WHERE column4 = column5', $this->getReflectionPropertyValue('where'));
    }

    /**
     * Test if 'having' works after 'using'.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_condition
     */
    public function testHavingWithJoinTypeUsing(): void
    {
        $this->setReflectionPropertyValue('join', 'INNER JOIN `table1` USING (column3)');
        $this->setReflectionPropertyValue('isUnfinishedJoin', FALSE);
        $this->setReflectionPropertyValue('joinType', 'using');

        $method = $this->getReflectionMethod('sql_condition');

        $method->invokeArgs($this->class, [ 'column4', 'column5', '=', 'HAVING' ]);

        $this->assertSame('INNER JOIN `table1` USING (column3)', $this->getReflectionPropertyValue('join'));
        $this->assertSame('HAVING column4 = column5', $this->getReflectionPropertyValue('having'));
    }

    /**
     * Test if 'having' works after 'on'.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_condition
     */
    public function testHavingWithJoinTypeOn(): void
    {
        $this->setReflectionPropertyValue('join', 'INNER JOIN `table1`ON (`column2` = `column3`)');
        $this->setReflectionPropertyValue('isUnfinishedJoin', FALSE);
        $this->setReflectionPropertyValue('joinType', 'on');

        $method = $this->getReflectionMethod('sql_condition');

        $method->invokeArgs($this->class, [ 'column4', 'column5', '=', 'HAVING' ]);

        $this->assertSame('INNER JOIN `table1`ON (`column2` = `column3`)', $this->getReflectionPropertyValue('join'));
        $this->assertSame('HAVING column4 = column5', $this->getReflectionPropertyValue('having'));
    }

    /**
     * Test if 'having' works with empty joinType.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_condition
     */
    public function testHavingWithJoinTypeEmpty(): void
    {
        $this->setReflectionPropertyValue('join', 'INNER JOIN `table1`ON (`column2` = `column3`)');
        $this->setReflectionPropertyValue('isUnfinishedJoin', FALSE);
        $this->setReflectionPropertyValue('joinType', '');

        $method = $this->getReflectionMethod('sql_condition');

        $method->invokeArgs($this->class, [ 'column4', 'column5', '=', 'HAVING' ]);

        $this->assertSame('INNER JOIN `table1`ON (`column2` = `column3`)', $this->getReflectionPropertyValue('join'));
        $this->assertSame('HAVING column4 = column5', $this->getReflectionPropertyValue('having'));
    }

    /**
     * Test start_on_group() after using join()->using().
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_group_start
     */
    public function testStartOnGroupWithJoinTypeUsing(): void
    {
        $this->setReflectionPropertyValue('join', 'INNER JOIN `table2` USING (`column1`)');
        $this->setReflectionPropertyValue('joinType', 'using');

        $method = $this->getReflectionMethod('sql_group_start');

        $method->invokeArgs($this->class, [ 'ON' ]);

        $this->assertSame('INNER JOIN `table2` USING (`column1`)', $this->getReflectionPropertyValue('join'));
    }

    /**
     * Test start_on_group() after using join()->using().
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_group_start
     */
    public function testStartOnGroupWithJoinTypeOn(): void
    {
        $this->setReflectionPropertyValue('join', 'INNER JOIN `table2` USING (`column1`)');
        $this->setReflectionPropertyValue('joinType', 'on');

        $method = $this->getReflectionMethod('sql_group_start');

        $method->invokeArgs($this->class, [ 'ON' ]);

        $this->assertSame('INNER JOIN `table2` USING (`column1`) AND (', $this->getReflectionPropertyValue('join'));
    }

    /**
     * Test start_on_group() after using join().
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_group_start
     */
    public function testStartOnGroupWithEmptyJoinType(): void
    {
        $this->setReflectionPropertyValue('join', 'INNER JOIN `table1`');
        $this->setReflectionPropertyValue('isUnfinishedJoin', TRUE);
        $this->setReflectionPropertyValue('joinType', '');

        $method = $this->getReflectionMethod('sql_group_start');

        $method->invokeArgs($this->class, [ 'ON' ]);

        $this->assertSame('INNER JOIN `table1`ON (', $this->getReflectionPropertyValue('join'));
        $this->assertFalse($this->getReflectionPropertyValue('isUnfinishedJoin'));
    }

    /**
     * Test start_having_group() after using join()->using().
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_group_start
     */
    public function testStartHavingGroupWithJoinTypeUsing(): void
    {
        $this->setReflectionPropertyValue('join', 'INNER JOIN `table1` USING (`column1`)');
        $this->setReflectionPropertyValue('isUnfinishedJoin', TRUE);
        $this->setReflectionPropertyValue('joinType', 'using');

        $method = $this->getReflectionMethod('sql_group_start');

        $method->invokeArgs($this->class, [ 'having' ]);

        $this->assertSame('INNER JOIN `table1` USING (`column1`)', $this->getReflectionPropertyValue('join'));
        $this->assertFalse($this->getReflectionPropertyValue('isUnfinishedJoin'));
    }

    /**
     * Test start_having_group() after using join()->using().
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_group_start
     */
    public function testStartHavingGroupWithJoinTypeOn(): void
    {
        $this->setReflectionPropertyValue('join', 'INNER JOIN `table1`ON (`column1` = `column2`)');
        $this->setReflectionPropertyValue('isUnfinishedJoin', TRUE);
        $this->setReflectionPropertyValue('joinType', 'on');

        $method = $this->getReflectionMethod('sql_group_start');

        $method->invokeArgs($this->class, [ 'having' ]);

        $this->assertSame('INNER JOIN `table1`ON (`column1` = `column2`)', $this->getReflectionPropertyValue('join'));
        $this->assertFalse($this->getReflectionPropertyValue('isUnfinishedJoin'));
    }

    /**
     * Test start_having_group() after using join()->using().
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_group_start
     */
    public function testStartHavingGroupWithJoinTypeEmpty(): void
    {
        $this->setReflectionPropertyValue('join', 'INNER JOIN `table1`ON (`column1` = `column2`)');
        $this->setReflectionPropertyValue('isUnfinishedJoin', TRUE);
        $this->setReflectionPropertyValue('joinType', '');

        $method = $this->getReflectionMethod('sql_group_start');

        $method->invokeArgs($this->class, [ 'having' ]);

        $this->assertSame('INNER JOIN `table1`ON (`column1` = `column2`)', $this->getReflectionPropertyValue('join'));
        $this->assertFalse($this->getReflectionPropertyValue('isUnfinishedJoin'));
    }

    /**
     * Test start_having_group() after using join().
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_group_start
     */
    public function testStartHavingGroupWithJoinTypeEmptyDoesntchangeJoinType(): void
    {
        $this->setReflectionPropertyValue('join', 'INNER JOIN `table1`');
        $this->setReflectionPropertyValue('isUnfinishedJoin', TRUE);
        $this->setReflectionPropertyValue('joinType', '');

        $method = $this->getReflectionMethod('sql_group_start');

        $method->invokeArgs($this->class, [ 'having' ]);

        $this->assertSame('', $this->getReflectionPropertyValue('joinType'));
    }

    /**
     * Test start_where_group() after using join()->using().
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_group_start
     */
    public function testStartWhereGroupWithJoinTypeUsing(): void
    {
        $this->setReflectionPropertyValue('join', 'INNER JOIN `table1` USING (`column1`)');
        $this->setReflectionPropertyValue('isUnfinishedJoin', TRUE);
        $this->setReflectionPropertyValue('joinType', 'using');

        $method = $this->getReflectionMethod('sql_group_start');

        $method->invokeArgs($this->class, [ 'where' ]);

        $this->assertSame('INNER JOIN `table1` USING (`column1`)', $this->getReflectionPropertyValue('join'));
        $this->assertFalse($this->getReflectionPropertyValue('isUnfinishedJoin'));
    }

    /**
     * Test start_where_group() after using join()->using().
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_group_start
     */
    public function testStartWhereGroupWithJoinTypeOn(): void
    {
        $this->setReflectionPropertyValue('join', 'INNER JOIN `table1`ON (`column1` = `column2`)');
        $this->setReflectionPropertyValue('isUnfinishedJoin', TRUE);
        $this->setReflectionPropertyValue('joinType', 'on');

        $method = $this->getReflectionMethod('sql_group_start');

        $method->invokeArgs($this->class, [ 'where' ]);

        $this->assertSame('INNER JOIN `table1`ON (`column1` = `column2`)', $this->getReflectionPropertyValue('join'));
        $this->assertFalse($this->getReflectionPropertyValue('isUnfinishedJoin'));
    }

    /**
     * Test start_where_group() after using join()->using().
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_group_start
     */
    public function testStartWhereGroupWithJoinTypeEmpty(): void
    {
        $this->setReflectionPropertyValue('join', 'INNER JOIN `table1`ON (`column1` = `column2`)');
        $this->setReflectionPropertyValue('isUnfinishedJoin', TRUE);
        $this->setReflectionPropertyValue('joinType', '');

        $method = $this->getReflectionMethod('sql_group_start');

        $method->invokeArgs($this->class, [ 'where' ]);

        $this->assertSame('INNER JOIN `table1`ON (`column1` = `column2`)', $this->getReflectionPropertyValue('join'));
        $this->assertFalse($this->getReflectionPropertyValue('isUnfinishedJoin'));
    }

    /**
     * Test start_where_group() after using join().
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_group_start
     */
    public function testStartWhereGroupWithJoinTypeEmptyDoesntchangeJoinType(): void
    {
        $this->setReflectionPropertyValue('join', 'INNER JOIN `table1`');
        $this->setReflectionPropertyValue('isUnfinishedJoin', TRUE);
        $this->setReflectionPropertyValue('joinType', '');

        $method = $this->getReflectionMethod('sql_group_start');

        $method->invokeArgs($this->class, [ 'where' ]);

        $this->assertSame('', $this->getReflectionPropertyValue('joinType'));
    }

    /**
     * Test if start_on_group() sets right join type.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_group_start
     */
    public function testStartOnGroupSetsRightJoinType(): void
    {
        $this->setReflectionPropertyValue('join', 'INNER JOIN `table1` USING (`column1`) INNER JOIN `table2`');
        $this->setReflectionPropertyValue('isUnfinishedJoin', TRUE);
        $this->setReflectionPropertyValue('joinType', '');

        $method = $this->getReflectionMethod('sql_group_start');

        $method->invokeArgs($this->class, [ 'ON' ]);

        $this->assertSame('on', $this->getReflectionPropertyValue('joinType'));
    }

    /**
     * Test if start_on_group() returns without affecting any data when wrong joinType is active.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_group_start
     */
    public function testStartOnGroupReturnsOnWrongJoinType(): void
    {
        $this->setReflectionPropertyValue('join', 'INNER JOIN `table1` USING (`column1`) INNER JOIN `table2`');
        $this->setReflectionPropertyValue('isUnfinishedJoin', TRUE);
        $this->setReflectionPropertyValue('joinType', 'using');
        $join = $this->getReflectionPropertyValue('join');

        $method = $this->getReflectionMethod('sql_group_start');

        $method->invokeArgs($this->class, [ 'ON' ]);

        $this->assertSame($join, $this->getReflectionPropertyValue('join'));
    }

    /**
     * Test sql_group_start() if joinType stays using.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_group_start
     */
    public function testStartOnGroupJoinTypeDoesntChange(): void
    {
        $this->setReflectionPropertyValue('join', 'INNER JOIN `table1` USING (`column1`) INNER JOIN `table2`');
        $this->setReflectionPropertyValue('isUnfinishedJoin', TRUE);
        $this->setReflectionPropertyValue('joinType', 'using');

        $method = $this->getReflectionMethod('sql_group_start');

        $method->invokeArgs($this->class, [ 'ON' ]);

        $this->assertSame('using', $this->getReflectionPropertyValue('joinType'));
    }

    /**
     * Test end_on_group() after using join()->using()->join()->group_on_start()-on().
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_group_end
     */
    public function testEndOnGroupWithJoinTypeEmpty(): void
    {
        $this->setReflectionPropertyValue('join', 'INNER JOIN `table1`');
        $this->setReflectionPropertyValue('isUnfinishedJoin', TRUE);
        $this->setReflectionPropertyValue('joinType', '');

        $method = $this->getReflectionMethod('sql_group_end');

        $method->invokeArgs($this->class, [ 'ON' ]);

        $this->assertSame('INNER JOIN `table1`)', $this->getReflectionPropertyValue('join'));
    }

    /**
     * Test end_on_group() after using join()->using().
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_group_end
     */
    public function testEndOnGroupWithJoinTypeUsing(): void
    {
        $this->setReflectionPropertyValue('join', 'INNER JOIN `table2` USING (`column1`)');
        $this->setReflectionPropertyValue('joinType', 'using');

        $method = $this->getReflectionMethod('sql_group_end');

        $method->invokeArgs($this->class, [ 'ON' ]);

        $this->assertSame('INNER JOIN `table2` USING (`column1`)', $this->getReflectionPropertyValue('join'));
    }

    /**
     * Test end_on_group() after using join()->using().
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_condition
     */
    public function testOnWithJoinTypeUsing(): void
    {
        $this->setReflectionPropertyValue('join', 'INNER JOIN `table1` USING (`column1`)');
        $this->setReflectionPropertyValue('isUnfinishedJoin', FALSE);
        $this->setReflectionPropertyValue('joinType', 'using');

        $method = $this->getReflectionMethod('sql_condition');

        $method->invokeArgs($this->class, [ 'column2', 'column3', '=', 'ON' ]);

        $this->assertSame('INNER JOIN `table1` USING (`column1`)', $this->getReflectionPropertyValue('join'));
        $this->assertFalse($this->getReflectionPropertyValue('isUnfinishedJoin'));
    }

    /**
     * Test end_on_group() after using join()-on().
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_condition
     */
    public function testOnWithJoinTypeOn(): void
    {
        $this->setReflectionPropertyValue('join', 'INNER JOIN `table1`ON (`column1` = `column2`)');
        $this->setReflectionPropertyValue('isUnfinishedJoin', FALSE);
        $this->setReflectionPropertyValue('joinType', 'on');

        $method = $this->getReflectionMethod('sql_condition');

        $method->invokeArgs($this->class, [ 'column2', 'column3', '=', 'ON' ]);

        $this->assertSame('INNER JOIN `table1`ON (`column1` = `column2`) AND column2 = column3', $this->getReflectionPropertyValue('join'));
        $this->assertFalse($this->getReflectionPropertyValue('isUnfinishedJoin'));
    }

    /**
     * Test on() with empty join type.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_condition
     */
    public function testOnWithJoinTypeEmpty(): void
    {
        $this->setReflectionPropertyValue('join', 'INNER JOIN `table1`');
        $this->setReflectionPropertyValue('isUnfinishedJoin', TRUE);
        $this->setReflectionPropertyValue('joinType', '');

        $method = $this->getReflectionMethod('sql_condition');

        $method->invokeArgs($this->class, [ 'column2', 'column3', '=', 'ON' ]);

        $this->assertSame('INNER JOIN `table1` ON column2 = column3', $this->getReflectionPropertyValue('join'));
        $this->assertFalse($this->getReflectionPropertyValue('isUnfinishedJoin'));
    }

    /**
     * Test if sql_condition() returns without affecting any data when wrong joinType is active.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_condition
     */
    public function testOnWithJoinTypeUsingDoesntChangeJoinType(): void
    {
        $this->setReflectionPropertyValue('join', 'INNER JOIN `table1` USING (`column1`)');
        $this->setReflectionPropertyValue('isUnfinishedJoin', FALSE);
        $this->setReflectionPropertyValue('joinType', 'using');

        $method = $this->getReflectionMethod('sql_condition');

        $method->invokeArgs($this->class, [ 'column2', 'column3', '=', 'ON' ]);

        $this->assertSame('using', $this->getReflectionPropertyValue('joinType'));
    }

    /**
     * Test if sql_condition() finishes join.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_condition
     */
    public function testOnSettingFinishedJoin(): void
    {
        $this->setReflectionPropertyValue('join', 'INNER JOIN `table1`');
        $this->setReflectionPropertyValue('isUnfinishedJoin', TRUE);
        $this->setReflectionPropertyValue('joinType', '');

        $method = $this->getReflectionMethod('sql_condition');

        $method->invokeArgs($this->class, [ 'column2', 'column3', '=', 'ON' ]);

        $this->assertFalse($this->getReflectionPropertyValue('isUnfinishedJoin'));
    }

    /**
     * Test if sql_condition() finishes join.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_condition
     */
    public function testWhereSettingFinishedJoin(): void
    {
        $this->setReflectionPropertyValue('join', 'INNER JOIN `table1`');
        $this->setReflectionPropertyValue('isUnfinishedJoin', TRUE);
        $this->setReflectionPropertyValue('joinType', '');

        $method = $this->getReflectionMethod('sql_condition');

        $method->invokeArgs($this->class, [ 'column2', 'column3', '=', 'WHERE' ]);

        $this->assertFalse($this->getReflectionPropertyValue('isUnfinishedJoin'));
    }

    /**
     * Test if sql_condition() finishes join.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_condition
     */
    public function testHavingSettingFinishedJoin(): void
    {
        $this->setReflectionPropertyValue('join', 'INNER JOIN `table1`');
        $this->setReflectionPropertyValue('isUnfinishedJoin', TRUE);
        $this->setReflectionPropertyValue('joinType', '');

        $method = $this->getReflectionMethod('sql_condition');

        $method->invokeArgs($this->class, [ 'column2', 'column3', '=', 'WHERE' ]);

        $this->assertFalse($this->getReflectionPropertyValue('isUnfinishedJoin'));
    }

    /**
     * Test if sql_condition() sets right joinType.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_condition
     */
    public function testOnSetRightJoinType(): void
    {
        $this->setReflectionPropertyValue('join', 'INNER JOIN `table1`');
        $this->setReflectionPropertyValue('isUnfinishedJoin', TRUE);
        $this->setReflectionPropertyValue('joinType', '');

        $method = $this->getReflectionMethod('sql_condition');

        $method->invokeArgs($this->class, [ 'column2', 'column3', '=', 'ON' ]);

        $this->assertSame('on', $this->getReflectionPropertyValue('joinType'));
    }

}

?>
