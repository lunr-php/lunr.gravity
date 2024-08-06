<?php

/**
 * This file contains the SQLite3QueryEscaperTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2012 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\SQLite3\Tests;

use Lunr\Gravity\SQLite3\SQLite3QueryEscaper;
use Lunr\Halo\LunrBaseTest;
use stdClass;

/**
 * This class contains the tests for the SQLite3QueryEscaper class.
 *
 * @covers Lunr\Gravity\SQLite3QueryEscaper
 */
abstract class SQLite3QueryEscaperTest extends LunrBaseTest
{

    /**
     * Mock instance of a class implementing the DatabaseStringEscaperInterface.
     * @var DatabaseStringEscaperInterface
     */
    protected $escaper;

    /**
     * Instance of the tested class.
     * @var SQLite3QueryEscaper
     */
    protected SQLite3QueryEscaper $class;

    /**
     * Testcase Constructor.
     */
    public function setUp(): void
    {
        $this->escaper = $this->getMockBuilder('Lunr\Gravity\DatabaseStringEscaperInterface')
                              ->getMock();

        $this->class = new SQLite3QueryEscaper($this->escaper);

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

    /**
     * Unit Test Data Provider for invalid indices.
     *
     * @return array $indices Array of invalid indices
     */
    public function invalidIndicesProvider(): array
    {
        $indices   = [];
        $indices[] = [ NULL ];
        $indices[] = [ FALSE ];
        $indices[] = [ 'string' ];
        $indices[] = [ new stdClass() ];
        $indices[] = [ [] ];

        return $indices;
    }

    /**
     * Unit Test Data Provider for valid Index Keywords.
     *
     * @return array $keywords Array of valid index keywords.
     */
    public function validIndexKeywordProvider(): array
    {
        $keywords   = [];
        $keywords[] = [ 'INDEXED BY' ];

        return $keywords;
    }

}

?>
