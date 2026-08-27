<?php

/**
 * This file contains the MariaDBQueryEscaperTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2026 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MariaDB\Tests;

use Lunr\Gravity\MariaDB\MariaDBQueryEscaper;
use Lunr\Halo\LunrBaseTestCase;

/**
 * This class contains common setup routines and shared attributes
 * for testing the MariaDBQueryEscaper class.
 *
 * @covers Lunr\Gravity\MariaDB\MariaDBQueryEscaper
 */
abstract class MariaDBQueryEscaperTestCase extends LunrBaseTestCase
{

    /**
     * Mock instance of a class implementing the DatabaseStringEscaperInterface.
     * @var DatabaseStringEscaperInterface
     */
    protected $escaper;

    /**
     * Instance of the tested class.
     * @var MariaDBQueryEscaper
     */
    protected MariaDBQueryEscaper $class;

    /**
     * Testcase Constructor.
     */
    public function setUp(): void
    {
        $this->escaper = $this->getMockBuilder('Lunr\Gravity\DatabaseStringEscaperInterface')
                              ->getMock();

        $this->class = new MariaDBQueryEscaper($this->escaper);

        parent::baseSetUp($this->class);
    }

    /**
     * Testcase Destructor.
     */
    public function tearDown(): void
    {
        unset($this->escaper);
        unset($this->class);

        parent::tearDown();
    }

}

?>
