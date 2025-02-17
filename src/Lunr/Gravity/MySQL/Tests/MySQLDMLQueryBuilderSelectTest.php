<?php

/**
 * This file contains the MySQLDMLQueryBuilderSelectTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2012 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MySQL\Tests;

/**
 * This class contains the tests for the query parts necessary to build
 * select queries.
 *
 * @covers Lunr\Gravity\MySQL\MySQLDMLQueryBuilder
 */
class MySQLDMLQueryBuilderSelectTest extends MySQLDMLQueryBuilderTestCase
{

    /**
     * Test that select modes for handling duplicate result entries are handled correctly.
     *
     * @param string $mode Valid select mode for handling duplicate entries.
     *
     * @dataProvider selectModesDuplicatesProvider
     * @covers       Lunr\Gravity\MySQL\MySQLDMLQueryBuilder::select_mode
     */
    public function testSelectModeSetsDuplicatesCorrectly($mode): void
    {
        $property = $this->builder_reflection->getProperty('selectMode');
        $property->setAccessible(TRUE);

        $this->builder->select_mode($mode);

        $modes = $property->getValue($this->builder);

        $this->assertEquals($mode, $modes['duplicates']);
    }

    /**
     * Test that select modes for handling the result cache are handled correctly.
     *
     * @param string $mode Valid select mode for handling the cache.
     *
     * @dataProvider selectModesCacheProvider
     * @covers       Lunr\Gravity\MySQL\MySQLDMLQueryBuilder::select_mode
     */
    public function testSelectModeSetsCacheCorrectly($mode): void
    {
        $property = $this->builder_reflection->getProperty('selectMode');
        $property->setAccessible(TRUE);

        $this->builder->select_mode($mode);

        $modes = $property->getValue($this->builder);

        $this->assertEquals($mode, $modes['cache']);
    }

    /**
     * Test that standard select modes are handled correctly.
     *
     * @param string $mode Valid select modes.
     *
     * @dataProvider selectModesStandardProvider
     * @covers       Lunr\Gravity\MySQL\MySQLDMLQueryBuilder::select_mode
     */
    public function testSelectModeSetsStandardCorrectly($mode): void
    {
        $property = $this->builder_reflection->getProperty('selectMode');
        $property->setAccessible(TRUE);

        $this->builder->select_mode($mode);

        $this->assertContains($mode, $property->getValue($this->builder));
    }

    /**
     * Test that unknown select modes are ignored.
     *
     * @covers Lunr\Gravity\MySQL\MySQLDMLQueryBuilder::select_mode
     */
    public function testSelectModeSetsIgnoresUnknownValues(): void
    {
        $property = $this->builder_reflection->getProperty('selectMode');
        $property->setAccessible(TRUE);

        $this->builder->select_mode('UNSUPPORTED');

        $value = $property->getValue($this->builder);

        $this->assertIsArray($value);
        $this->assertEmpty($value);
    }

    /**
     * Test that standard lock modes are handled correctly.
     *
     * @param string $mode Valid select modes.
     *
     * @dataProvider lockModesStandardProvider
     * @covers       Lunr\Gravity\MySQL\MySQLDMLQueryBuilder::lock_mode
     */
    public function testLockModeSetsStandardCorrectly($mode): void
    {
        $property = $this->builder_reflection->getProperty('lockMode');
        $property->setAccessible(TRUE);

        $this->builder->lock_mode($mode);

        $this->assertStringMatchesFormat($mode, $property->getValue($this->builder));
    }

    /**
     * Test that unknown lock modes are ignored.
     *
     * @covers Lunr\Gravity\MySQL\MySQLDMLQueryBuilder::lock_mode
     */
    public function testLockModeSetsIgnoresUnknownValues(): void
    {
        $property = $this->builder_reflection->getProperty('lockMode');
        $property->setAccessible(TRUE);

        $this->builder->lock_mode('UNSUPPORTED');

        $value = $property->getValue($this->builder);

        $this->assertEmpty($value);
    }

    /**
     * Test that there can be only one duplicate handling select mode set.
     *
     * @depends testSelectModeSetsDuplicatesCorrectly
     * @covers  Lunr\Gravity\MySQL\MySQLDMLQueryBuilder::select_mode
     */
    public function testSelectModeAllowsOnlyOneDuplicateStatement(): void
    {
        $property = $this->builder_reflection->getProperty('selectMode');
        $property->setAccessible(TRUE);

        $this->builder->select_mode('ALL');
        $this->builder->select_mode('DISTINCT');

        $modes = $property->getValue($this->builder);

        $this->assertEquals('DISTINCT', $modes['duplicates']);
        $this->assertNotContains('ALL', $modes);
    }

    /**
     * Test that there can be only one cache handling select mode set.
     *
     * @depends testSelectModeSetsCacheCorrectly
     * @covers  Lunr\Gravity\MySQL\MySQLDMLQueryBuilder::select_mode
     */
    public function testSelectModeAllowsOnlyOneCacheStatement(): void
    {
        $property = $this->builder_reflection->getProperty('selectMode');
        $property->setAccessible(TRUE);

        $this->builder->select_mode('SQL_NO_CACHE');
        $this->builder->select_mode('SQL_CACHE');

        $modes = $property->getValue($this->builder);

        $this->assertEquals('SQL_CACHE', $modes['cache']);
        $this->assertNotContains('SQL_NO_CACHE', $modes);
    }

    /**
     * Test fluid interface of the select_mode method.
     *
     * @covers Lunr\Gravity\MySQL\MySQLDMLQueryBuilder::select_mode
     */
    public function testSelectModeReturnsSelfReference(): void
    {
        $return = $this->builder->select_mode('DISTINCT');

        $this->assertInstanceOf('Lunr\Gravity\MySQL\MySQLDMLQueryBuilder', $return);
        $this->assertSame($this->builder, $return);
    }

}

?>
