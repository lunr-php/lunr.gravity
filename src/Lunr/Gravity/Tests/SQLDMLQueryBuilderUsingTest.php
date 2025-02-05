<?php

/**
 * This file contains the SQLDMLQueryBuilderUsingTest class.
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
class SQLDMLQueryBuilderUsingTest extends SQLDMLQueryBuilderTest
{

    /**
     * Test using().
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::using
     */
    public function testUsing(): void
    {
        $this->setReflectionPropertyValue('is_unfinished_join', TRUE);

        $this->class->using('column1');

        $string = ' USING (column1)';

        $this->assertPropertyEquals('join', $string);
    }

    /**
     * Test using().
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::using
     */
    public function testUsingAddSecondColumn(): void
    {
        $this->setReflectionPropertyValue('join_type', 'using');
        $this->setReflectionPropertyValue('join', ' USING (column1)');

        $this->class->using('column2');

        $string = ' USING (column1, column2)';

        $this->assertPropertyEquals('join', $string);
    }

    /**
     * Test using().
     *
     * @covers Lunr\Gravity\SQLDMLQueryBuilder::using
     */
    public function testUsingMultipleColumn(): void
    {
        $this->setReflectionPropertyValue('is_unfinished_join', TRUE);

        $this->class->using('column1, column2');

        $string = ' USING (column1, column2)';

        $this->assertPropertyEquals('join', $string);
    }

}

?>
