<?php

/**
 * This file contains the DatabaseDMLQueryBuilderQueryPartsTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2012 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\Tests;

use Lunr\Gravity\DatabaseDMLQueryBuilder;

/**
 * This class contains the tests for the query parts methods.
 *
 * @covers Lunr\Gravity\DatabaseDMLQueryBuilder
 */
class DatabaseDMLQueryBuilderQueryPartsTest extends DatabaseDMLQueryBuilderTest
{

    /**
     * Test specifying the UNION part of a query.
     *
     * @param string      $types    Compound query operator
     * @param string|null $operator Compound query operator
     *
     * @dataProvider compoundQueryTypeAndOperatorProvider
     * @covers       Lunr\Gravity\DatabaseDMLQueryBuilder::sql_compound
     */
    public function testCompoundQuery($types, $operator = NULL): void
    {
        $method = $this->get_accessible_reflection_method('sql_compound');

        $method->invokeArgs($this->class, [ '(sql query)', $types, $operator ]);

        if ($operator === NULL)
        {
            $string = $types . ' (sql query)';
        }
        else
        {
            $string = $types . ' ' . $operator . ' (sql query)';
        }

        $this->assertPropertyEquals('compound', $string);
    }

    /**
     * Test specifying the UNION part of a query with invalid parameters.
     *
     * @param string          $types    Compound query operator
     * @param string|int|bool $operator Compound query operator
     *
     * @dataProvider compoundQueryInvalidTypeAndOperatorProvider
     * @covers       Lunr\Gravity\DatabaseDMLQueryBuilder::sql_compound
     */
    public function testCompoundQueryUnsupportedOperators($types, $operator): void
    {
        $method = $this->get_accessible_reflection_method('sql_compound');

        $method->invokeArgs($this->class, [ '(sql query)', $types, $operator ]);

        $string = $types . ' (sql query)';

        $this->assertPropertyEquals('compound', $string);
    }

    /**
     * Test specifying the UNION part of a query, when compound is set.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_compound
     */
    public function testCompoundQueryWhenCompoundIsSet(): void
    {
        $this->set_reflection_property_value('compound', 'QUERY');

        $method = $this->get_accessible_reflection_method('sql_compound');

        $method->invokeArgs($this->class, [ '(sql query)', 'UNION' ]);

        $this->assertPropertyEquals('compound', 'QUERY UNION (sql query)');
    }

    /**
     * Test specifying the EXCEPT part of a query, when compound is set.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_compound
     */
    public function testCompoundQueryWhenCompoundIsSetWithExcept(): void
    {
        $this->set_reflection_property_value('compound', 'QUERY');

        $method = $this->get_accessible_reflection_method('sql_compound');

        $method->invokeArgs($this->class, [ '(sql query)', 'EXCEPT' ]);

        $this->assertPropertyEquals('compound', 'QUERY EXCEPT (sql query)');
    }

    /**
     * Test specifying the INTERSECT part of a query, when compound is set.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_compound
     */
    public function testCompoundQueryWhenCompoundIsSetWithIntersect(): void
    {
        $this->set_reflection_property_value('compound', 'QUERY');

        $method = $this->get_accessible_reflection_method('sql_compound');

        $method->invokeArgs($this->class, [ '(sql query)', 'INTERSECT' ]);

        $this->assertPropertyEquals('compound', 'QUERY INTERSECT (sql query)');
    }

    /**
     * Test creating a simple order by statement.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_order_by
     */
    public function testOrderByWithDefaultOrder(): void
    {
        $string = 'ORDER BY col1 ASC';

        $method = $this->get_accessible_reflection_method('sql_order_by');

        $method->invokeArgs($this->class, [ 'col1' ]);

        $this->assertPropertyEquals('order_by', $string);
    }

    /**
     * Test creating a order by statement with custom order.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_order_by
     */
    public function testOrderByWithCustomOrder(): void
    {
        $string = 'ORDER BY col1 DESC';

        $method = $this->get_accessible_reflection_method('sql_order_by');

        $method->invokeArgs($this->class, [ 'col1', FALSE ]);

        $this->assertPropertyEquals('order_by', $string);
    }

    /**
     * Test creating and extending a order by statement.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_order_by
     */
    public function testOrderByWithExtendedStatement(): void
    {
        $value = 'ORDER BY col1 DESC';

        $this->set_reflection_property_value('order_by', $value);

        $method = $this->get_accessible_reflection_method('sql_order_by');

        $method->invokeArgs($this->class, [ 'col2', FALSE ]);

        $string = 'ORDER BY col1 DESC, col2 DESC';

        $this->assertPropertyEquals('order_by', $string);
    }

    /**
     * Test creating a simple group by statement.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_group_by
     */
    public function testGroupBy(): void
    {
        $string = 'GROUP BY group1';

        $method = $this->get_accessible_reflection_method('sql_group_by');

        $method->invokeArgs($this->class, [ 'group1' ]);

        $this->assertPropertyEquals('group_by', $string);
    }

    /**
     * Test creating and extending a group by statement.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_group_by
     */
    public function testGroupByExtending(): void
    {
        $value = 'GROUP BY group1';

        $this->set_reflection_property_value('group_by', $value);

        $method = $this->get_accessible_reflection_method('sql_group_by');

        $method->invokeArgs($this->class, [ 'group2' ]);

        $string = 'GROUP BY group1, group2';

        $this->assertPropertyEquals('group_by', $string);
    }

    /**
     * Test creating a limit statement with default offset.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_limit
     */
    public function testLimitWithDefaultOffset(): void
    {
        $string = 'LIMIT 10';

        $method = $this->get_accessible_reflection_method('sql_limit');

        $method->invokeArgs($this->class, [ '10' ]);

        $this->assertPropertyEquals('limit', $string);
    }

    /**
     * Test creating a limit statement with custom offset.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_limit
     */
    public function testLimitWithCustomOffset(): void
    {
        $string = 'LIMIT 10 OFFSET 20';

        $method = $this->get_accessible_reflection_method('sql_limit');

        $method->invokeArgs($this->class, [ '10', '20' ]);

        $this->assertPropertyEquals('limit', $string);
    }

    /**
    * Test grouping condition start.
    *
    * @param string $keyword   The expected statement keyword
    * @param string $attribute The name of the property where the statement is stored
    *
    * @dataProvider conditionalKeywordProvider
    * @covers       Lunr\Gravity\DatabaseDMLQueryBuilder::sql_group_start
    */
    public function testOpenGroup($keyword, $attribute): void
    {
        $method = $this->get_accessible_reflection_method('sql_group_start');
        $method->invokeArgs($this->class, [ $keyword ]);

        $this->assertEquals('(', $this->get_reflection_property_value($attribute));
    }

    /**
    * Test grouping condition start with active join statement.
    *
    * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_group_start
    */
    public function testOpenGroupIfJoin(): void
    {
        $this->set_reflection_property_value('is_unfinished_join', TRUE);

        $method = $this->get_accessible_reflection_method('sql_group_start');
        $method->invokeArgs($this->class, [ 'ON' ]);

        $this->assertEquals('ON (', $this->get_reflection_property_value('join'));
        $this->assertFalse($this->get_reflection_property_value('is_unfinished_join'));
    }

    /**
     * Test grouping condition start with closed join statement.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_group_start
     */
    public function testOpenGroupIfNaturalJoin(): void
    {
        $this->set_reflection_property_value('is_unfinished_join', FALSE);

        $method = $this->get_accessible_reflection_method('sql_group_start');
        $method->invokeArgs($this->class, [ 'WHERE' ]);

        $this->assertEquals('', $this->get_reflection_property_value('join'));
    }

    /**
    * Test grouping condition start.
    *
    * @param string $keyword   The expected statement keyword
    * @param string $attribute The name of the property where the statement is stored
    *
    * @dataProvider conditionalKeywordProvider
    * @covers       Lunr\Gravity\DatabaseDMLQueryBuilder::sql_group_start
    */
    public function testOpenGroupWithConnector($keyword, $attribute): void
    {
        $this->set_reflection_property_value('connector', 'OR');
        $this->set_reflection_property_value($attribute, 'a = b');

        $method = $this->get_accessible_reflection_method('sql_group_start');
        $method->invokeArgs($this->class, [ $keyword ]);

        $this->assertEquals('a = b OR (', $this->get_reflection_property_value($attribute));
        $this->assertEquals('', $this->get_reflection_property_value('connector'));
    }

    /**
    * Test grouping condition start when condition is empty.
    *
    * @param string $keyword   The expected statement keyword
    * @param string $attribute The name of the property where the statement is stored
    *
    * @dataProvider conditionalKeywordProvider
    * @covers       Lunr\Gravity\DatabaseDMLQueryBuilder::sql_group_start
    */
    public function testOpenGroupWithConnectorWhenConditionIsEmpty($keyword, $attribute): void
    {
        $this->set_reflection_property_value('connector', 'OR');

        $method = $this->get_accessible_reflection_method('sql_group_start');
        $method->invokeArgs($this->class, [ $keyword ]);

        $this->assertEquals('(', $this->get_reflection_property_value($attribute));
        $this->assertEquals('', $this->get_reflection_property_value('connector'));
    }

    /**
    * Test incremental grouping condition start.
    *
    * @param string $keyword   The expected statement keyword
    * @param string $attribute The name of the property where the statement is stored
    *
    * @dataProvider conditionalKeywordProvider
    * @covers       Lunr\Gravity\DatabaseDMLQueryBuilder::sql_group_start
    */
    public function testIncrementalOpenGroup($keyword, $attribute): void
    {
        $method = $this->get_accessible_reflection_method('sql_group_start');
        $method->invokeArgs($this->class, [ $keyword ]);
        $method->invokeArgs($this->class, [ $keyword ]);

        $this->assertEquals('((', $this->get_reflection_property_value($attribute));
    }

    /**
    * Test grouping condition start uses AND connector by default if there is already a condition.
    *
    * @param string $keyword   The expected statement keyword
    * @param string $attribute The name of the property where the statement is stored
    *
    * @dataProvider conditionalKeywordProvider
    * @covers       Lunr\Gravity\DatabaseDMLQueryBuilder::sql_group_start
    */
    public function testOpenGroupUsesDefaultConnector($keyword, $attribute): void
    {
        $this->set_reflection_property_value($attribute, 'Condition');

        $method = $this->get_accessible_reflection_method('sql_group_start');
        $method->invokeArgs($this->class, [ $keyword ]);

        $this->assertEquals('Condition AND (', $this->get_reflection_property_value($attribute));
    }

    /**
     * Test closing the parentheses for grouped condition.
     *
     * @param string $keyword   The expected statement keyword
     * @param string $attribute The name of the property where the statement is stored
     *
     * @dataProvider conditionalKeywordProvider
     * @covers       Lunr\Gravity\DatabaseDMLQueryBuilder::sql_group_end
     */
    public function testCloseGroup($keyword, $attribute): void
    {
        $this->set_reflection_property_value($attribute, '');

        $method = $this->get_accessible_reflection_method('sql_group_end');
        $method->invokeArgs($this->class, [ $keyword ]);

        $this->assertEquals(')', $this->get_reflection_property_value($attribute));
    }

}

?>
