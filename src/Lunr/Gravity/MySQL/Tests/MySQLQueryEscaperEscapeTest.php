<?php

/**
 * This file contains the MySQLQueryEscaperEscapeTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2012 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MySQL\Tests;

/**
 * This class contains the tests for escaping values in queries.
 *
 * @covers Lunr\Gravity\MySQL\MySQLQueryEscaper
 */
class MySQLQueryEscaperEscapeTest extends MySQLQueryEscaperTestCase
{

    /**
     * Test escaping a simple value.
     *
     * @depends Lunr\Gravity\Tests\DatabaseQueryEscaperEscapeTest::testCollateWithValueOnly
     * @covers  Lunr\Gravity\MySQL\MySQLQueryEscaper::value
     */
    public function testEscapingValue(): void
    {
        $this->escaper->expects($this->once())
                      ->method('escape_string')
                      ->willReturn('value');

        $this->assertEquals('\'value\'', $this->class->value('value'));
    }

    /**
     * Test escaping a value with a collation specified.
     *
     * @depends Lunr\Gravity\Tests\DatabaseQueryEscaperEscapeTest::testCollateWithCollation
     * @covers  Lunr\Gravity\MySQL\MySQLQueryEscaper::value
     */
    public function testEscapingValueWithCollation(): void
    {
        $this->escaper->expects($this->once())
                      ->method('escape_string')
                      ->willReturn('value');

        $this->assertEquals('\'value\' COLLATE utf8_general_ci', $this->class->value('value', 'utf8_general_ci'));
    }

    /**
     * Test escaping a value with charset specified.
     *
     * @depends Lunr\Gravity\Tests\DatabaseQueryEscaperEscapeTest::testCollateWithValueOnly
     * @covers  Lunr\Gravity\MySQL\MySQLQueryEscaper::value
     */
    public function testEscapingValueWithCharset(): void
    {
        $this->escaper->expects($this->once())
                      ->method('escape_string')
                      ->willReturn('value');

        $this->assertEquals('ascii \'value\'', $this->class->value('value', '', 'ascii'));
    }

    /**
     * Test escaping a value with a collation and charset specified.
     *
     * @depends Lunr\Gravity\Tests\DatabaseQueryEscaperEscapeTest::testCollateWithCollation
     * @covers  Lunr\Gravity\MySQL\MySQLQueryEscaper::value
     */
    public function testEscapingValueWithCollationAndCharset(): void
    {
        $this->escaper->expects($this->once())
                      ->method('escape_string')
                      ->willReturn('value');

        $string = 'ascii \'value\' COLLATE utf8_general_ci';

        $this->assertEquals($string, $this->class->value('value', 'utf8_general_ci', 'ascii'));
    }

    /**
     * Test escaping a hex value.
     *
     * @depends Lunr\Gravity\Tests\DatabaseQueryEscaperEscapeTest::testCollateWithValueOnly
     * @covers  Lunr\Gravity\MySQL\MySQLQueryEscaper::hexvalue
     */
    public function testEscapingHexValue(): void
    {
        $this->escaper->expects($this->once())
                      ->method('escape_string')
                      ->willReturn('value');

        $this->assertEquals('UNHEX(\'value\')', $this->class->hexvalue('value'));
    }

    /**
     * Test escaping a hex value with a collation specified.
     *
     * @depends Lunr\Gravity\Tests\DatabaseQueryEscaperEscapeTest::testCollateWithCollation
     * @covers  Lunr\Gravity\MySQL\MySQLQueryEscaper::hexvalue
     */
    public function testEscapingHexValueWithCollation(): void
    {
        $this->escaper->expects($this->once())
                      ->method('escape_string')
                      ->willReturn('value');

        $string = 'UNHEX(\'value\') COLLATE utf8_general_ci';

        $this->assertEquals($string, $this->class->hexvalue('value', 'utf8_general_ci'));
    }

    /**
     * Test escaping a hex value with charset specified.
     *
     * @depends Lunr\Gravity\Tests\DatabaseQueryEscaperEscapeTest::testCollateWithValueOnly
     * @covers  Lunr\Gravity\MySQL\MySQLQueryEscaper::hexvalue
     */
    public function testEscapingHexValueWithCharset(): void
    {
        $this->escaper->expects($this->once())
                      ->method('escape_string')
                      ->willReturn('value');

        $this->assertEquals('ascii UNHEX(\'value\')', $this->class->hexvalue('value', '', 'ascii'));
    }

    /**
     * Test escaping a hex value with a collation and charset specified.
     *
     * @depends Lunr\Gravity\Tests\DatabaseQueryEscaperEscapeTest::testCollateWithCollation
     * @covers  Lunr\Gravity\MySQL\MySQLQueryEscaper::hexvalue
     */
    public function testEscapingHexValueWithCollationAndCharset(): void
    {
        $this->escaper->expects($this->once())
                      ->method('escape_string')
                      ->willReturn('value');

        $string = 'ascii UNHEX(\'value\') COLLATE utf8_general_ci';

        $this->assertEquals($string, $this->class->hexvalue('value', 'utf8_general_ci', 'ascii'));
    }

    /**
     * Test escaping a default like value.
     *
     * @depends Lunr\Gravity\Tests\DatabaseQueryEscaperEscapeTest::testCollateWithValueOnly
     * @covers  Lunr\Gravity\MySQL\MySQLQueryEscaper::likevalue
     */
    public function testEscapingLikeValue(): void
    {
        $this->escaper->expects($this->once())
                      ->method('escape_string')
                      ->willReturn('value');

        $this->assertEquals('\'%value%\'', $this->class->likevalue('value'));
    }

    /**
     * Test escaping a default like value with a collation specified.
     *
     * @depends Lunr\Gravity\Tests\DatabaseQueryEscaperEscapeTest::testCollateWithCollation
     * @covers  Lunr\Gravity\MySQL\MySQLQueryEscaper::likevalue
     */
    public function testEscapingLikeValueWithCollation(): void
    {
        $this->escaper->expects($this->once())
                      ->method('escape_string')
                      ->willReturn('value');

        $string = '\'%value%\' COLLATE utf8_general_ci';

        $this->assertEquals($string, $this->class->likevalue('value', 'both', 'utf8_general_ci'));
    }

    /**
     * Test escaping a default like value with charset specified.
     *
     * @depends Lunr\Gravity\Tests\DatabaseQueryEscaperEscapeTest::testCollateWithValueOnly
     * @covers  Lunr\Gravity\MySQL\MySQLQueryEscaper::likevalue
     */
    public function testEscapingLikeValueWithCharset(): void
    {
        $this->escaper->expects($this->once())
                      ->method('escape_string')
                      ->willReturn('value');

        $this->assertEquals('ascii \'%value%\'', $this->class->likevalue('value', 'both', '', 'ascii'));
    }

    /**
     * Test escaping a default like value with a collation and charset specified.
     *
     * @depends Lunr\Gravity\Tests\DatabaseQueryEscaperEscapeTest::testCollateWithCollation
     * @covers  Lunr\Gravity\MySQL\MySQLQueryEscaper::likevalue
     */
    public function testEscapingLikeValueWithCollationAndCharset(): void
    {
        $this->escaper->expects($this->once())
                      ->method('escape_string')
                      ->willReturn('value');

        $string = 'ascii \'%value%\' COLLATE utf8_general_ci';

        $this->assertEquals($string, $this->class->likevalue('value', 'both', 'utf8_general_ci', 'ascii'));
    }

    /**
     * Test escaping a forward like value.
     *
     * @depends Lunr\Gravity\Tests\DatabaseQueryEscaperEscapeTest::testCollateWithValueOnly
     * @covers  Lunr\Gravity\MySQL\MySQLQueryEscaper::likevalue
     */
    public function testEscapingLikeValueForward(): void
    {
        $this->escaper->expects($this->once())
                      ->method('escape_string')
                      ->willReturn('value');

        $this->assertEquals('\'value%\'', $this->class->likevalue('value', 'forward'));
    }

    /**
     * Test escaping a backward like value.
     *
     * @depends Lunr\Gravity\Tests\DatabaseQueryEscaperEscapeTest::testCollateWithValueOnly
     * @covers  Lunr\Gravity\MySQL\MySQLQueryEscaper::likevalue
     */
    public function testEscapingLikeValueBackward(): void
    {
        $this->escaper->expects($this->once())
                      ->method('escape_string')
                      ->willReturn('value');

        $this->assertEquals('\'%value\'', $this->class->likevalue('value', 'backward'));
    }

    /**
     * Test escaping a unsupported like value.
     *
     * @depends Lunr\Gravity\Tests\DatabaseQueryEscaperEscapeTest::testCollateWithValueOnly
     * @covers  Lunr\Gravity\MySQL\MySQLQueryEscaper::likevalue
     */
    public function testEscapingLikeValueUnsupported(): void
    {
        $this->escaper->expects($this->once())
                      ->method('escape_string')
                      ->willReturn('value');

        $this->assertEquals('\'%value%\'', $this->class->likevalue('value', 'unsupported'));
    }

    /**
     * Test escaping an index hint with invalid indices.
     *
     * @param mixed $indices Invalid indices value
     *
     * @dataProvider invalidIndicesProvider
     * @covers       Lunr\Gravity\MySQL\MySQLQueryEscaper::index_hint
     */
    public function testEscapingIndexHintWithInvalidIndices($indices): void
    {
        $this->assertNull($this->class->index_hint('', $indices));
    }

    /**
     * Test escaping an index hint with valid keywords.
     *
     * @param string $keyword Valid index keyword
     *
     * @dataProvider validIndexKeywordProvider
     * @depends      Lunr\Gravity\Tests\DatabaseQueryEscaperEscapeTest::testEscapeLocationReference
     * @covers       Lunr\Gravity\MySQL\MySQLQueryEscaper::index_hint
     */
    public function testEscapingIndexHintWithValidKeyword($keyword): void
    {
        $indices = [ 'index', 'index' ];

        $value = $this->class->index_hint($keyword, $indices);

        $this->assertEquals($keyword . ' INDEX (`index`, `index`)', $value);
    }

    /**
     * Test escaping an index hint with an invalid keyword.
     *
     * @depends Lunr\Gravity\Tests\DatabaseQueryEscaperEscapeTest::testEscapeLocationReference
     * @covers  Lunr\Gravity\MySQL\MySQLQueryEscaper::index_hint
     */
    public function testEscapingIndexHintWithInvalidKeyword(): void
    {
        $indices = [ 'index', 'index' ];

        $value = $this->class->index_hint('invalid', $indices);

        $this->assertEquals('USE INDEX (`index`, `index`)', $value);
    }

    /**
     * Test escaping an index hint with valid use definition.
     *
     * @param string $for Valid use definition
     *
     * @dataProvider validIndexForProvider
     * @depends      Lunr\Gravity\Tests\DatabaseQueryEscaperEscapeTest::testEscapeLocationReference
     * @covers       Lunr\Gravity\MySQL\MySQLQueryEscaper::index_hint
     */
    public function testEscapingIndexHintWithValidFor($for): void
    {
        $indices = [ 'index', 'index' ];

        $value = $this->class->index_hint('USE', $indices, $for);

        if ($for === '')
        {
            $this->assertEquals('USE INDEX (`index`, `index`)', $value);
        }
        else
        {
            $this->assertEquals('USE INDEX FOR ' . $for . ' (`index`, `index`)', $value);
        }
    }

    /**
     * Test escaping an index hint with an invalid use definition.
     *
     * @depends Lunr\Gravity\Tests\DatabaseQueryEscaperEscapeTest::testEscapeLocationReference
     * @covers  Lunr\Gravity\MySQL\MySQLQueryEscaper::index_hint
     */
    public function testEscapingIndexHintWithInvalidFor(): void
    {
        $indices = [ 'index', 'index' ];

        $value = $this->class->index_hint('invalid', $indices, 'invalid');

        $this->assertEquals('USE INDEX (`index`, `index`)', $value);
    }

    /**
     * Test escaping a geometric value.
     *
     * @covers Lunr\Gravity\MySQL\MySQLQueryEscaper::geovalue
     */
    public function testEscapingGeoValueWithoutSrid(): void
    {
        $this->escaper->expects($this->once())
                      ->method('escape_string')
                      ->willReturn('value');

        $this->assertEquals("ST_GeomFromText('value')", $this->class->geovalue('value'));
    }

    /**
     * Test escaping a geometric value with passing a SRID.
     *
     * @covers Lunr\Gravity\MySQL\MySQLQueryEscaper::geovalue
     */
    public function testEscapingGeoValueWithSrid(): void
    {
        $this->escaper->expects($this->once())
                      ->method('escape_string')
                      ->willReturn('value');

        $this->assertEquals("ST_GeomFromText('value', 5)", $this->class->geovalue('value', 5));
    }

}

?>
