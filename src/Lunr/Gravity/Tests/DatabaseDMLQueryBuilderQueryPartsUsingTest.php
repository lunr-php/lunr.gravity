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
        $this->setReflectionPropertyValue('is_unfinished_join', TRUE);

        $method->invokeArgs($this->class, [ 'column1' ]);

        $string = ' USING (column1)';

        $this->assertSame($string, $this->getReflectionPropertyValue('join'));
    }

    /**
     * Test sql_using() with join_type using.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_using
     */
    public function testUsingAppendsNewColumnithJoinTypeUsing(): void
    {
        $method = $this->getReflectionMethod('sql_using');
        $this->setReflectionPropertyValue('join', 'INNER JOIN `table2` USING (column1)');
        $this->setReflectionPropertyValue('is_unfinished_join', FALSE);
        $this->setReflectionPropertyValue('join_type', 'using');

        $method->invokeArgs($this->class, [ 'column2' ]);

        $string = 'INNER JOIN `table2` USING (column1, column2)';

        $this->assertSame($string, $this->getReflectionPropertyValue('join'));
    }

    /**
     * Test sql_using() if join_type stays using.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_using
     */
    public function testUsingJoinTypeStaysUsing(): void
    {
        $method = $this->getReflectionMethod('sql_using');
        $this->setReflectionPropertyValue('join', 'INNER JOIN `table2` USING (column1)');
        $this->setReflectionPropertyValue('is_unfinished_join', FALSE);
        $this->setReflectionPropertyValue('join_type', 'using');

        $method->invokeArgs($this->class, [ 'column2' ]);

        $this->assertSame('using', $this->getReflectionPropertyValue('join_type'));
    }

    /**
     * Test sql_using().
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_using
     */
    public function testUsingMultipleColumn(): void
    {
        $method = $this->getReflectionMethod('sql_using');
        $this->setReflectionPropertyValue('is_unfinished_join', TRUE);

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
        $this->setReflectionPropertyValue('is_unfinished_join', TRUE);
        $this->setReflectionPropertyValue('join_type', 'on');

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
        $this->setReflectionPropertyValue('is_unfinished_join', TRUE);

        $method->invokeArgs($this->class, [ 'column1' ]);

        $this->assertFalse($this->getReflectionPropertyValue('is_unfinished_join'));
    }

    /**
     * Test if sql_using() sets the right type.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_using
     */
    public function testUsingSetCorrectJoinType(): void
    {
        $method = $this->getReflectionMethod('sql_using');
        $this->setReflectionPropertyValue('is_unfinished_join', TRUE);
        $this->setReflectionPropertyValue('join_type', '');

        $method->invokeArgs($this->class, [ 'column1' ]);

        $this->assertSame('using', $this->getReflectionPropertyValue('join_type'));
    }

    /**
     * Test if sql_using() sets the right type.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_using
     */
    public function testUsingSetDoesntChangeJoinType(): void
    {
        $method = $this->getReflectionMethod('sql_using');
        $this->setReflectionPropertyValue('is_unfinished_join', TRUE);
        $this->setReflectionPropertyValue('join_type', 'on');

        $method->invokeArgs($this->class, [ 'column1' ]);

        $this->assertSame('on', $this->getReflectionPropertyValue('join_type'));
    }

    /**
     * Test if sql_using() returns without affecting any data when wrong join_type is active.
     *
     * @covers Lunr\Gravity\DatabaseDMLQueryBuilder::sql_using
     */
    public function testUsingReturnsIfWrongJoinType(): void
    {
        $method = $this->getReflectionMethod('sql_using');
        $this->setReflectionPropertyValue('is_unfinished_join', TRUE);
        $this->setReflectionPropertyValue('join_type', 'on');
        $join = $this->getReflectionPropertyValue('join');

        $method->invokeArgs($this->class, [ 'column1' ]);

        $this->assertSame('on', $this->getReflectionPropertyValue('join_type'));
        $this->assertSame($join, $this->getReflectionPropertyValue('join'));
    }

}

?>
