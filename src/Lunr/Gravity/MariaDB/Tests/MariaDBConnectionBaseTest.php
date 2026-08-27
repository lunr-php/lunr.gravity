<?php

/**
 * This file contains the MariaDBConnectionBaseTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2018 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MariaDB\Tests;

/**
 * This class contains basic tests for the MariaDBConnection class
 *
 * @covers Lunr\Gravity\MariaDB\MariaDBConnection
 */
class MariaDBConnectionBaseTest extends MariaDBConnectionTestCase
{

    /**
     * Test getting a new DMLQueryBuilder.
     *
     * @covers Lunr\Gravity\MariaDB\MariaDBConnection::get_new_dml_query_builder_object
     */
    public function testGetDMLQueryBuilder(): void
    {
        $querybuilder = $this->class->get_new_dml_query_builder_object(FALSE);

        $instance = 'Lunr\Gravity\MariaDB\MariaDBDMLQueryBuilder';
        $this->assertInstanceOf($instance, $querybuilder);
    }

    /**
     * Test getting a new DMLQueryBuilder.
     *
     * @covers Lunr\Gravity\MariaDB\MariaDBConnection::get_new_dml_query_builder_object
     */
    public function testGetSimpleDMLQueryBuilder(): void
    {
        $querybuilder = $this->class->get_new_dml_query_builder_object(TRUE);

        $instance = 'Lunr\Gravity\MariaDB\MariaDBSimpleDMLQueryBuilder';
        $this->assertInstanceOf($instance, $querybuilder);
    }

    /**
     * Test that get_query_escaper_object() returns a new object.
     *
     * @covers Lunr\Gravity\MariaDB\MariaDBConnection::get_query_escaper_object
     */
    public function testGetQueryEscaperObjectReturnsObject(): void
    {
        $value = $this->class->get_query_escaper_object();

        $this->assertInstanceOf('Lunr\Gravity\DatabaseQueryEscaper', $value);
        $this->assertInstanceOf('Lunr\Gravity\MariaDB\MariaDBQueryEscaper', $value);
    }

    /**
     * Test that get_query_escaper_object() caches the object.
     *
     * @covers Lunr\Gravity\MariaDB\MariaDBConnection::get_query_escaper_object
     */
    public function testGetQueryEscaperObjectCachesObject(): void
    {
        $this->assertPropertyUnset('escaper');

        $this->class->get_query_escaper_object();

        $property = $this->getReflectionProperty('escaper');
        $instance = 'Lunr\Gravity\MariaDB\MariaDBQueryEscaper';
        $this->assertInstanceOf($instance, $property->getValue($this->class));
    }

    /**
     * Test that get_query_escaper_object() returns the cached object.
     *
     * @covers Lunr\Gravity\MariaDB\MariaDBConnection::get_query_escaper_object
     */
    public function testGetQueryEscaperObjectReturnsCachedObject(): void
    {
        $value1 = $this->class->get_query_escaper_object();
        $value2 = $this->class->get_query_escaper_object();

        $this->assertInstanceOf('Lunr\Gravity\MariaDB\MariaDBQueryEscaper', $value1);
        $this->assertSame($value1, $value2);
    }

}

?>
