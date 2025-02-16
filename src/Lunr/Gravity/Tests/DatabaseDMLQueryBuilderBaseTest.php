<?php

/**
 * This file contains the DatabaseDMLQueryBuilderBaseTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2012 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\Tests;

/**
 * This class contains the tests for the setup and the final query creation.
 *
 * @covers Lunr\Gravity\DatabaseDMLQueryBuilder
 */
class DatabaseDMLQueryBuilderBaseTest extends DatabaseDMLQueryBuilderTestCase
{

    /**
     * Test that select is an empty string by default.
     */
    public function testSelectEmptyByDefault(): void
    {
        $this->assertPropertyEquals('select', '');
    }

    /**
     * Test that selectMode is an empty array by default.
     */
    public function testSelectModeEmptyByDefault(): void
    {
        $this->assertArrayEmpty($this->getReflectionPropertyValue('selectMode'));
    }

    /**
     * Test that update is an empty string by default.
     */
    public function testUpdateEmptyByDefault(): void
    {
        $this->assertPropertyEquals('update', '');
    }

    /**
     * Test that updateMode is an empty array by default.
     */
    public function testUpdateModeEmptyByDefault(): void
    {
        $this->assertArrayEmpty($this->getReflectionPropertyValue('updateMode'));
    }

    /**
     * Test that delete is an empty string by default.
     */
    public function testDeleteEmptyByDefault(): void
    {
        $this->assertPropertyEquals('delete', '');
    }

    /**
     * Test that deleteMode is an empty array by default.
     */
    public function testDeleteModeEmptyByDefault(): void
    {
        $this->assertArrayEmpty($this->getReflectionPropertyValue('deleteMode'));
    }

    /**
     * Test that insertMode is an empty array by default.
     */
    public function testInsertModeEmptyByDefault(): void
    {
        $this->assertArrayEmpty($this->getReflectionPropertyValue('insertMode'));
    }

    /**
     * Test that into is an empty string by default.
     */
    public function testIntoEmptyByDefault(): void
    {
        $this->assertPropertyEquals('into', '');
    }

    /**
     * Test that set is an empty string by default.
     */
    public function testSetEmptyByDefault(): void
    {
        $this->assertPropertyEquals('set', '');
    }

    /**
     * Test that columnNames is an empty string by default.
     */
    public function testColumnNamesEmptyByDefault(): void
    {
        $this->assertPropertyEquals('columnNames', '');
    }

    /**
     * Test that values is an empty string by default.
     */
    public function testValuesEmptyByDefault(): void
    {
        $this->assertPropertyEquals('values', '');
    }

    /**
     * Test that upsert is an empty string by default.
     */
    public function testUpsertEmptyByDefault()
    {
        $this->assertPropertyEquals('upsert', '');
    }

    /**
     * Test that selectStatement is an empty string by default.
     */
    public function testSelectStatementEmptyByDefault(): void
    {
        $this->assertPropertyEquals('selectStatement', '');
    }

    /**
     * Test that from is an empty string by default.
     */
    public function testFromEmptyByDefault(): void
    {
        $this->assertPropertyEquals('from', '');
    }

    /**
     * Test that orderBy is an empty string by default.
     */
    public function testOrderByEmptyByDefault(): void
    {
        $this->assertPropertyEquals('orderBy', '');
    }

    /**
     * Test that groupBy is an empty string by default.
     */
    public function testGroupByEmptyByDefault(): void
    {
        $this->assertPropertyEquals('groupBy', '');
    }

    /**
     * Test that limit is an empty string by default.
     */
    public function testLimitEmptyByDefault(): void
    {
        $this->assertPropertyEquals('limit', '');
    }

    /**
     * Test that with is an empty string by default.
     */
    public function testWithEmptyByDefault(): void
    {
        $this->assertPropertyEquals('with', '');
    }

    /**
     * Test that prepare_index_hints prepares valid index hints correctly.
     *
     * @param array  $hints    Array of index hints
     * @param string $expected Expected escaped string
     *
     * @dataProvider validIndexHintProvider
     * @covers       Lunr\Gravity\DatabaseDMLQueryBuilder::prepare_index_hints
     */
    public function testPrepareValidIndexHints($hints, $expected): void
    {
        $method = $this->getReflectionMethod('prepare_index_hints');

        $this->assertEquals($expected, $method->invokeArgs($this->class, [ $hints ]));
    }

    /**
     * Test that prepare_index_hints returns an empty string for invalid input.
     *
     * @param mixed $hints Invalid value
     *
     * @dataProvider invalidIndexHintProvider
     * @covers       Lunr\Gravity\DatabaseDMLQueryBuilder::prepare_index_hints
     */
    public function testPrepareInvalidIndexHintsReturnsEmptyString($hints): void
    {
        $method = $this->getReflectionMethod('prepare_index_hints');

        $this->assertEquals('', $method->invokeArgs($this->class, [ $hints ]));
    }

}
?>
