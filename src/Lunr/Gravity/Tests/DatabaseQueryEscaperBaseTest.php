<?php

/**
 * This file contains the DatabaseQueryEscaperBaseTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2012 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\Tests;

/**
 * This class contains the tests for the DatabaseQueryEscaper class.
 *
 * @covers Lunr\Gravity\DatabaseQueryEscaper
 */
class DatabaseQueryEscaperBaseTest extends DatabaseQueryEscaperTestCase
{

    /**
     * Test that DatabaseStringEscaper class is passed.
     */
    public function testDatabaseStringEscaperIsPassed(): void
    {
        $this->assertSame($this->escaper, $this->getReflectionPropertyValue('escaper'));
    }

}

?>
