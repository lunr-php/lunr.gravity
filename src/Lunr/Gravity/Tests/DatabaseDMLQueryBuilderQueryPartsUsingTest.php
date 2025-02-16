<?php

/**
 * This file contains the DatabaseDMLQueryBuilderQueryPartsUsingTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2016 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\Tests;

/**
 * This class contains the tests for the query parts methods.
 *
 * @covers Lunr\Gravity\DatabaseDMLQueryBuilder
 */
class DatabaseDMLQueryBuilderQueryPartsUsingTest extends DatabaseDMLQueryBuilderTestCase
{

    /**
     * Test sql_using().
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_using
     */
    public function testUsingWithJoinTypeEmpty(): void
    {
        $method = $this->getReflectionMethod('sql_using');
        $this->setReflectionPropertyValue('isUnfinishedJoin', TRUE);

        $method->invokeArgs($this->class, [ 'column1' ]);

        $string = ' USING (column1)';

        $this->assertSame($string, $this->getReflectionPropertyValue('join'));
    }

    /**
     * Test sql_using() with joinType using.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_using
     */
    public function testUsingAppendsNewColumnithJoinTypeUsing(): void
    {
        $method = $this->getReflectionMethod('sql_using');
        $this->setReflectionPropertyValue('join', 'INNER JOIN `table2` USING (column1)');
        $this->setReflectionPropertyValue('isUnfinishedJoin', FALSE);
        $this->setReflectionPropertyValue('joinType', 'using');

        $method->invokeArgs($this->class, [ 'column2' ]);

        $string = 'INNER JOIN `table2` USING (column1, column2)';

        $this->assertSame($string, $this->getReflectionPropertyValue('join'));
    }

    /**
     * Test sql_using() if joinType stays using.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_using
     */
    public function testUsingJoinTypeStaysUsing(): void
    {
        $method = $this->getReflectionMethod('sql_using');
        $this->setReflectionPropertyValue('join', 'INNER JOIN `table2` USING (column1)');
        $this->setReflectionPropertyValue('isUnfinishedJoin', FALSE);
        $this->setReflectionPropertyValue('joinType', 'using');

        $method->invokeArgs($this->class, [ 'column2' ]);

        $this->assertSame('using', $this->getReflectionPropertyValue('joinType'));
    }

    /**
     * Test sql_using().
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_using
     */
    public function testUsingMultipleColumn(): void
    {
        $method = $this->getReflectionMethod('sql_using');
        $this->setReflectionPropertyValue('isUnfinishedJoin', TRUE);

        $method->invokeArgs($this->class, [ 'column1, column2' ]);

        $string = ' USING (column1, column2)';

        $this->assertSame($string, $this->getReflectionPropertyValue('join'));
    }

    /**
     * Test sql_using() after using join()->on().
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_using
     */
    public function testUsingWithJoinTypeOn(): void
    {
        $method = $this->getReflectionMethod('sql_using');

        $this->setReflectionPropertyValue('join', 'INNER JOIN `table2`ON (`column3` = `column4`)');
        $this->setReflectionPropertyValue('isUnfinishedJoin', TRUE);
        $this->setReflectionPropertyValue('joinType', 'on');

        $method->invokeArgs($this->class, [ 'column1' ]);

        $this->assertSame('INNER JOIN `table2`ON (`column3` = `column4`)', $this->getReflectionPropertyValue('join'));
    }

    /**
     * Test sql_using() after using join().
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_using
     */
    public function testUsingFinishedJoin(): void
    {
        $method = $this->getReflectionMethod('sql_using');

        $this->setReflectionPropertyValue('join', 'INNER JOIN `table2`');
        $this->setReflectionPropertyValue('isUnfinishedJoin', TRUE);

        $method->invokeArgs($this->class, [ 'column1' ]);

        $this->assertFalse($this->getReflectionPropertyValue('isUnfinishedJoin'));
    }

    /**
     * Test if sql_using() sets the right type.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_using
     */
    public function testUsingSetCorrectJoinType(): void
    {
        $method = $this->getReflectionMethod('sql_using');
        $this->setReflectionPropertyValue('isUnfinishedJoin', TRUE);
        $this->setReflectionPropertyValue('joinType', '');

        $method->invokeArgs($this->class, [ 'column1' ]);

        $this->assertSame('using', $this->getReflectionPropertyValue('joinType'));
    }

    /**
     * Test if sql_using() sets the right type.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_using
     */
    public function testUsingSetDoesntChangeJoinType(): void
    {
        $method = $this->getReflectionMethod('sql_using');
        $this->setReflectionPropertyValue('isUnfinishedJoin', TRUE);
        $this->setReflectionPropertyValue('joinType', 'on');

        $method->invokeArgs($this->class, [ 'column1' ]);

        $this->assertSame('on', $this->getReflectionPropertyValue('joinType'));
    }

    /**
     * Test if sql_using() returns without affecting any data when wrong joinType is active.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_using
     */
    public function testUsingReturnsIfWrongJoinType(): void
    {
        $method = $this->getReflectionMethod('sql_using');
        $this->setReflectionPropertyValue('isUnfinishedJoin', TRUE);
        $this->setReflectionPropertyValue('joinType', 'on');
        $join = $this->getReflectionPropertyValue('join');

        $method->invokeArgs($this->class, [ 'column1' ]);

        $this->assertSame('on', $this->getReflectionPropertyValue('joinType'));
        $this->assertSame($join, $this->getReflectionPropertyValue('join'));
    }

}

?>
