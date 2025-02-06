<?php

/**
 * This file contains the SQLite3DMLQueryBuilderDeleteTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2013 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\SQLite3\Tests;

/**
 * This class contains the tests for the query parts necessary to build
 * delete queries.
 *
 * @covers Lunr\Gravity\SQLite3\SQLite3DMLQueryBuilder
 */
class SQLite3DMLQueryBuilderDeleteTest extends SQLite3DMLQueryBuilderTestCase
{

    /**
     * Test that unknown delete modes are ignored.
     *
     * @covers Lunr\Gravity\SQLite3\SQLite3DMLQueryBuilder::delete_mode
     */
    public function testDeleteModeIgnoresUnknownValues(): void
    {
        $this->class->delete_mode('UNSUPPORTED');
        $value = $this->getReflectionPropertyValue('delete_mode');

        $this->assertIsArray($value);
        $this->assertEmpty($value);
    }

    /**
     * Test fluid interface of the delete_mode method.
     *
     * @covers Lunr\Gravity\SQLite3\SQLite3DMLQueryBuilder::delete_mode
     */
    public function testDeleteModeReturnsSelfReference(): void
    {
        $return = $this->class->delete_mode('IGNORE');

        $this->assertInstanceOf('Lunr\Gravity\SQLite3\SQLite3DMLQueryBuilder', $return);
        $this->assertSame($this->class, $return);
    }

}

?>
