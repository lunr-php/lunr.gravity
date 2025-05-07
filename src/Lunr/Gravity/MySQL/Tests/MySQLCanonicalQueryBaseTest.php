<?php

/**
 * This file contains the MySQLConnectionEscapeTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2021 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MySQL\Tests;

use Lunr\Gravity\MySQL\MySQLCanonicalQuery;

/**
 * This class contains unit tests for MySQLCanonicalQuery.
 *
 * @covers Lunr\Gravity\MySQL\MySQLCanonicalQuery
 */
class MySQLCanonicalQueryBaseTest extends MySQLCanonicalQueryTest
{

    /**
     * Test that find_positions() returns the correct positions.
     *
     * @param array $data     Data values
     * @param array $expected Modified data values
     *
     * @dataProvider findPositionsDataProvider
     * @covers       Lunr\Gravity\MySQL\MySQLCanonicalQuery::find_positions
     */
    public function testFindPositions(array $data, array $expected): void
    {
        $property = $this->get_accessible_reflection_property('ignore_positions');
        $property->setValue($this->class, ($data[1] ?? []));

        $method = $this->get_accessible_reflection_method('find_positions');

        $result = $method->invokeArgs($this->class, $data[0] );
        $this->assertEquals($expected, $result);
    }

    /**
     * Test that remove_eol_blank_spaces() returns string without double blank spaces and end of line characters.
     *
     * @param string $data     Data values
     * @param string $expected Modified values
     *
     * @dataProvider removeEolBlankSpacesDataProvider
     * @covers       Lunr\Gravity\MySQL\MySQLCanonicalQuery::remove_eol_blank_spaces
     */
    public function testRemoveEolBlankSpaces(string $data, string $expected): void
    {
        $method = $this->get_accessible_reflection_method('remove_eol_blank_spaces');

        $result = $method->invokeArgs($this->class, [ $data ]);
        $this->assertEquals($expected, $result);
    }

    /**
     * Test that escape_string() properly escapes the given string.
     *
     * @param array $data     Data values
     * @param array $expected Modified values
     *
     * @dataProvider updatePositionsDataProvider
     * @covers       Lunr\Gravity\MySQL\MySQLCanonicalQuery::update_positions
     */
    public function testUpdatePositions(array $data, array $expected): void
    {
        $method = $this->get_accessible_reflection_method('update_positions');

        $result = $method->invokeArgs($this->class, $data);

        $this->assertEquals($expected, $result);
    }

    /**
     * Test that find_digit() finds next digit if exists
     *
     * @param array $data     Data values
     * @param mixed $expected Modified values
     *
     * @dataProvider findDigitDataProvider
     * @covers       Lunr\Gravity\MySQL\MySQLCanonicalQuery::find_digit
     */
    public function testFindDigit(array $data, $expected): void
    {
        $property = $this->get_accessible_reflection_property('ignore_positions');
        $property->setValue($this->class, ($data[1] ?? []));

        $method = $this->get_accessible_reflection_method('find_digit');

        $result = $method->invokeArgs($this->class, $data[0]);

        $this->assertEquals($expected, $result);
    }

    /**
     * Test that jump_ignore() returns the index after positions to ignore or the same index case is not between ranges
     *
     * @param array $data     Data values
     * @param int   $expected Modified values
     *
     * @dataProvider jumpIgnoreDataProvider
     * @covers       Lunr\Gravity\MySQL\MySQLCanonicalQuery::jump_ignore
     */
    public function testJumpIgnore(array $data, int $expected): void
    {
        $property = $this->get_accessible_reflection_property('ignore_positions');
        $property->setValue($this->class, ($data[1] ?? []));

        $method = $this->get_accessible_reflection_method('jump_ignore');

        $result = $method->invokeArgs($this->class, [ $data[0] ]);

        $this->assertEquals($expected, $result);
    }

    /**
     * Test that is_numeric_value() returns true and the end position of the numeric value, and false if its not numeric
     *
     * @param array $data     Data values
     * @param array $expected Modified data values
     *
     * @dataProvider isNumericValueDataProvider
     * @covers       Lunr\Gravity\MySQL\MySQLCanonicalQuery::is_numeric_value
     */
    public function testIsNumericValue(array $data, array $expected): void
    {
        $method = $this->get_accessible_reflection_method('is_numeric_value');

        $result = $method->invokeArgs($this->class, $data);

        $this->assertIsBool($result[0]);
        $this->assertIsInt($result[1]);

        $this->assertEquals($expected, $result);
    }

    /**
     * Test that replace_between() returns replaced string correctly.
     *
     * @param array  $data     Data values
     * @param string $expected Modified data values
     *
     * @dataProvider replaceBetweenDataProvider
     * @covers       Lunr\Gravity\MySQL\MySQLCanonicalQuery::replace_between
     */
    public function testReplaceBetween(array $data, string $expected): void
    {
        $property = $this->get_accessible_reflection_property('ignore_positions');
        $property->setValue($this->class, ($data[1] ?? []));

        $method = $this->get_accessible_reflection_method('replace_between');

        $result = $method->invokeArgs($this->class, $data[0]);

        $this->assertEquals($expected, $result);
    }

    /**
     * Test that add_ignore_positions() returns true and the end position of the numeric value, and false if its not numeric
     *
     * @covers Lunr\Gravity\MySQL\MySQLCanonicalQuery::add_ignore_positions
     */
    public function testAddIgnorePositions(): void
    {
        $property = $this->get_accessible_reflection_property('ignore_positions');
        $property->setValue($this->class, []);

        $method = $this->get_accessible_reflection_method('add_ignore_positions');
        $method->invokeArgs($this->class, [[[ 40,50 ]]]);
        $method->invokeArgs($this->class, [[[ 3,10 ],[ 25,30 ]]]);

        $result = $property->getValue($this->class);

        $this->assertIsArray($result);
        $this->assertEquals([[ 3,10 ],[ 25,30 ],[ 40,50 ]], $result);
    }

    /**
     * Test that is_negative_number()
     *
     * @param array $data     Data values
     * @param bool  $expected Modified data values
     *
     * @dataProvider isNegativeNumberDataProvider
     * @covers       Lunr\Gravity\MySQL\MySQLCanonicalQuery::is_negative_number
     */
    public function testIsNegativeNumber(array $data, bool $expected): void
    {
        $method = $this->get_accessible_reflection_method('is_negative_number');

        $result = $method->invokeArgs($this->class, $data);
        $this->assertIsBool($result);
        $this->assertEquals($expected, $result);
    }

    /**
     * Test that replace_numeric() returns the query string without numeric values and does not remove numeric non values,
     * numeric values includes numeric, decimal, exponential, hexadecimal, negative
     *
     * @param array  $data     Data values
     * @param string $expected Modified data values
     *
     * @dataProvider replaceNumericDataProvider
     * @covers       Lunr\Gravity\MySQL\MySQLCanonicalQuery::replace_numeric
     */
    public function testReplaceNumeric(array $data, string $expected): void
    {
        $property = $this->get_accessible_reflection_property('ignore_positions');
        $property->setValue($this->class, ($data[1] ?? []));

        $method = $this->get_accessible_reflection_method('replace_numeric');

        $result = $method->invokeArgs($this->class, $data[0]);

        $this->assertEquals($expected, $result);
    }

    /**
     * Test that get_canonical_query() returns the canonical query.
     *
     * @param string $data     Data values
     * @param string $expected Modified data values
     *
     * @dataProvider canonicalQueryDataProvider
     * @covers       Lunr\Gravity\MySQL\MySQLCanonicalQuery::get_canonical_query
     */
    public function testCanonicalQuery(string $data, string $expected): void
    {
        if (!file_exists($data))
        {
            $this->markTestSkipped($data . ' does not exist.');
            return;
        }

        if (!file_exists($expected))
        {
            $this->markTestSkipped($expected . ' does not exist.');
            return;
        }

        $input  = file_get_contents($data);
        $output = file_get_contents($expected);

        if ($input === FALSE)
        {
            $this->markTestSkipped("File \"$data\" could not be read!");
        }

        if ($output === FALSE)
        {
            $this->markTestSkipped("File \"$expected\" could not be read!");
        }

        $class = new MySQLCanonicalQuery($input);

        $value = $class->get_canonical_query();

        $this->assertEquals($output, $value . "\n");
    }

    /**
     * Test that get_canonical_query() returns the canonical query.
     *
     * @covers Lunr\Gravity\MySQL\MySQLCanonicalQuery::get_canonical_query
     */
    public function testCanonicalQueryWithUnixLineEnding(): void
    {
        $input  = file_get_contents(TEST_STATICS . '/Gravity/Database/MySQL/input_unix_lf.sql');
        $output = file_get_contents(TEST_STATICS . '/Gravity/Database/MySQL/output_unix_lf.sql');

        $this->assertStringContainsString("\n", $input);
        $this->assertStringNotContainsString("\r", $input);

        $class = new MySQLCanonicalQuery($input);

        $value = $class->get_canonical_query();

        $this->assertEquals($output, $value . "\n");
    }

    /**
     * Test that get_canonical_query() returns the canonical query.
     *
     * @covers Lunr\Gravity\MySQL\MySQLCanonicalQuery::get_canonical_query
     */
    public function testCanonicalQueryWithWindowsLineEnding(): void
    {
        $input  = file_get_contents(TEST_STATICS . '/Gravity/Database/MySQL/input_win_crlf.sql');
        $output = file_get_contents(TEST_STATICS . '/Gravity/Database/MySQL/output_win_crlf.sql');

        $this->assertStringContainsString("\r\n", $input);

        $class = new MySQLCanonicalQuery($input);

        $value = $class->get_canonical_query();

        $this->assertEquals($output, $value . "\n");
    }

    /**
     * Test that get_canonical_query() returns the canonical query.
     *
     * @covers Lunr\Gravity\MySQL\MySQLCanonicalQuery::get_canonical_query
     */
    public function testCanonicalQueryWithMacLineEnding(): void
    {
        $input  = file_get_contents(TEST_STATICS . '/Gravity/Database/MySQL/input_mac_cr.sql');
        $output = file_get_contents(TEST_STATICS . '/Gravity/Database/MySQL/output_mac_cr.sql');

        $this->assertStringContainsString("\r", $input);
        $this->assertStringNotContainsString("\n", $input);

        $class = new MySQLCanonicalQuery($input);

        $value = $class->get_canonical_query();

        $this->assertEquals($output, $value . "\n");
    }

    /**
     * Test that get_between_delimiter() returns the range of index between the next start and end delimiters provided
     *
     * @param array       $data     Data values
     * @param string|null $expected Modified data values
     *
     * @dataProvider getBetweenDelimiterDataProvider
     * @covers       Lunr\Gravity\MySQL\MySQLCanonicalQuery::get_between_delimiter
     */
    public function testGetBetweenDelimiter(array $data, ?array $expected): void
    {
        $method = $this->get_accessible_reflection_method('get_between_delimiter');

        $result = $method->invokeArgs($this->class, $data);

        $this->assertEquals($expected, $result);
    }

    /**
     * Test that find_next() returns the index of the string provided,
     *
     * @param array    $data     Data values
     * @param int|null $expected Modified data values
     *
     * @dataProvider findNextDataProvider
     * @covers       Lunr\Gravity\MySQL\MySQLCanonicalQuery::find_next
     */
    public function testFindNext(array $data, ?int $expected): void
    {
        $property = $this->get_accessible_reflection_property('ignore_positions');
        $property->setValue($this->class, ($data[1] ?? []));

        $method = $this->get_accessible_reflection_method('find_next');

        $result = $method->invokeArgs($this->class, $data[0]);

        $this->assertEquals($expected, $result);
    }

    /**
     * Test that collapse_multi_rows() returns the canonical query collapsed.
     *
     * @param string $data     Data values
     * @param string $expected Modified data values
     *
     * @dataProvider collapseMultiRowInsertsDataProvider
     * @covers       Lunr\Gravity\MySQL\MySQLCanonicalQuery::collapse_multirows
     */
    public function testCollapseMultiRows(string $data, string $expected): void
    {
        if (!file_exists($data))
        {
            $this->markTestSkipped($data . ' does not exist.');
            return;
        }

        if (!file_exists($expected))
        {
            $this->markTestSkipped($expected . ' does not exist.');
            return;
        }

        $input  = file_get_contents($data);
        $output = file_get_contents($expected);

        if ($input === FALSE)
        {
            $this->markTestSkipped("File \"$data\" could not be read!");
        }

        if ($output === FALSE)
        {
            $this->markTestSkipped("File \"$expected\" could not be read!");
        }

        $property = $this->get_accessible_reflection_property('ignore_positions');
        $property->setValue($this->class, []);

        $method = $this->get_accessible_reflection_method('collapse_multirows');

        $result = $method->invokeArgs($this->class, [ $input ]);

        $this->assertEquals($output, $result);
    }

}

?>
