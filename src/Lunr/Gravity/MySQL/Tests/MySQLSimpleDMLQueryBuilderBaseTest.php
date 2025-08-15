<?php

/**
 * This file contains the MySQLSimpleDMLQueryBuilderBaseTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2013 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MySQL\Tests;

/**
 * This class contains basic tests for the MySQLSimpleDMLQueryBuilder class.
 *
 * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder
 */
class MySQLSimpleDMLQueryBuilderBaseTest extends MySQLSimpleDMLQueryBuilderTestCase
{

    /**
     * Test the QueryEscaper class is passed correctly.
     */
    public function testEscaperIsPassedCorrectly(): void
    {
        $instance = 'Lunr\Gravity\MySQL\MySQLQueryEscaper';
        $this->assertInstanceOf($instance, $this->getReflectionPropertyValue('escaper'));
    }

    /**
     * Test if the query builder has been passed correctly.
     */
    public function testQuerybuilderPassedCorrectly(): void
    {
        $instance = 'Lunr\Gravity\MySQL\MySQLDMLQueryBuilder';
        $this->assertInstanceOf($instance, $this->getReflectionPropertyValue('builder'));
    }

    /**
     * Test escape_alias() with aliased references.
     *
     * @param string $input    Location reference
     * @param bool   $type     Whether to escape a Table or a Result column
     * @param string $name     Reference name
     * @param string $alias    Alias
     * @param string $expected Expected escaped string
     *
     * @dataProvider locationReferenceAliasProvider
     * @covers       Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::escape_alias
     */
    public function testEscapeAliasWithAlias(string $input, bool $type, string $name, string $alias, string $expected): void
    {
        $method = $type ? 'table' : 'result_column';

        $this->escaper->expects($method)
                      ->once()
                      ->with($name, $alias)
                      ->andReturn($expected);

        $method = $this->getReflectionMethod('escape_alias');

        $result = $method->invokeArgs($this->class, [ $input, $type ]);

        $this->assertEquals($result, $expected);
    }

    /**
     * Test escape_alias() with plain references.
     *
     * @param string $input    Location reference
     * @param bool   $type     Whether to escape a Table or a Result column
     * @param string $expected Expected escaped string
     *
     * @dataProvider locationReferenceProvider
     * @covers       Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder::escape_alias
     */
    public function testEscapeAliasPlain(string $input, bool $type, string $expected): void
    {
        $method = $type ? 'table' : 'result_column';

        $this->escaper->expects($method)
                      ->once()
                      ->with($input)
                      ->andReturn($expected);

        $method = $this->getReflectionMethod('escape_alias');

        $result = $method->invokeArgs($this->class, [ $input, $type ]);

        $this->assertEquals($result, $expected);
    }

}

?>
