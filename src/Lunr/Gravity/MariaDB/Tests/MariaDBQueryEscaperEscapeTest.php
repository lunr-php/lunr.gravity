<?php

/**
 * This file contains the MariaDBQueryEscaperEscapeTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2026 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MariaDB\Tests;

/**
 * This class contains the tests for escaping values in queries.
 *
 * @covers Lunr\Gravity\MariaDB\MariaDBQueryEscaper
 */
class MariaDBQueryEscaperEscapeTest extends MariaDBQueryEscaperTestCase
{

    /**
     * Test that row_alias() ignores the alias, since MariaDB doesn't support it (MDEV-29919).
     *
     * @covers Lunr\Gravity\MariaDB\MariaDBQueryEscaper::row_alias
     */
    public function testEscapingRowAliasIsUnsupported(): void
    {
        $value = $this->class->row_alias('new_row');

        $this->assertSame('', $value);
    }

}

?>
