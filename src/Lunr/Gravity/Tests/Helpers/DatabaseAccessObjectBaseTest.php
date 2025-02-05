<?php

/**
 * This file contains the DatabaseAccessObjectBaseTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2023 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\Tests\Helpers;

use Lunr\Halo\LunrBaseTestCase;

/**
 * This class contains setup and tear down methods for DAOs using MySQL access.
 */
abstract class DatabaseAccessObjectBaseTest extends LunrBaseTestCase
{

    /**
     * Testcase Destructor.
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Reports an error if the value of $actualSql does not match the value in $expectedFile.
     *
     * @param string $expectedFile File containing the (optionally pretty-printed) expected SQL query
     * @param string $actualSql    The actual SQL query string
     *
     * @return void
     */
    public function assertSqlStringEqualsSqlFile($expectedFile, $actualSql): void
    {
        $formatted = file_get_contents($expectedFile);
        $formatted = preg_replace('/\s--.*/', '', $formatted);
        $formatted = trim(preg_replace('/\s+/', ' ', $formatted));
        $formatted = str_replace('( ', '(', $formatted);
        $formatted = str_replace(' )', ')', $formatted);

        $this->assertEquals($formatted, $actualSql);
    }

}

?>
