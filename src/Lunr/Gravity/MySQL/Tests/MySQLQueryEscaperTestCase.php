<?php

/**
 * This file contains the MySQLQueryEscaperTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2012 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MySQL\Tests;

use Lunr\Gravity\MySQL\MySQLQueryEscaper;
use Lunr\Halo\LunrBaseTestCase;

/**
 * This class contains the tests for the MySQLQueryEscaper class.
 *
 * @covers Lunr\Gravity\MySQLQueryEscaper
 */
abstract class MySQLQueryEscaperTestCase extends LunrBaseTestCase
{

    /**
     * Mock instance of a class implementing the DatabaseStringEscaperInterface.
     * @var DatabaseStringEscaperInterface
     */
    protected $escaper;

    /**
     * Instance of the tested class.
     * @var MySQLQueryEscaper
     */
    protected MySQLQueryEscaper $class;

    /**
     * Testcase Constructor.
     */
    public function setUp(): void
    {
        $this->escaper = $this->getMockBuilder('Lunr\Gravity\DatabaseStringEscaperInterface')
                              ->getMock();

        $this->class = new MySQLQueryEscaper($this->escaper);

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
        $keywords[] = [ 'USE' ];
        $keywords[] = [ 'IGNORE' ];
        $keywords[] = [ 'FORCE' ];

        return $keywords;
    }

    /**
     * Unit Test Data Provider for valid Index use definitions.
     *
     * @return array $for Array of valid index use definitions.
     */
    public function validIndexForProvider(): array
    {
        $for   = [];
        $for[] = [ 'JOIN' ];
        $for[] = [ 'ORDER BY' ];
        $for[] = [ 'GROUP BY' ];
        $for[] = [ '' ];

        return $for;
    }

}

?>
