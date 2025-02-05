<?php

/**
 * This file contains the SQLite3DMLQueryBuilderTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2013 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\SQLite3\Tests;

use Lunr\Gravity\SQLite3\SQLite3DMLQueryBuilder;
use Lunr\Halo\LunrBaseTestCase;

/**
 * This class contains common setup routines, providers
 * and shared attributes for testing the SQLite3DMLQueryBuilder class.
 *
 * @covers Lunr\Gravity\SQLite3\SQLite3DMLQueryBuilder
 */
abstract class SQLite3DMLQueryBuilderTest extends LunrBaseTestCase
{

    /**
     * Instance of the tested class.
     * @var SQLite3DMLQueryBuilder
     */
    protected SQLite3DMLQueryBuilder $class;

    /**
     * TestCase Constructor.
     */
    public function setUp(): void
    {
        $this->class = new SQLite3DMLQueryBuilder();

        parent::baseSetUp($this->class);
    }

    /**
     * TestCase Destructor.
     */
    public function tearDown(): void
    {
        unset($this->class);

        parent::tearDown();
    }

    /**
     * Unit Test Data Provider for Select modes handling duplicate result entries.
     *
     * @return array $modes Array of select modes
     */
    public function selectModesDuplicatesProvider(): array
    {
        $modes   = [];
        $modes[] = [ 'ALL' ];
        $modes[] = [ 'DISTINCT' ];

        return $modes;
    }

    /**
     * Unit Test Data Provider for standard Select modes.
     *
     * @return array $modes Array of select modes
     */
    public function modesProvider(): array
    {
        $modes   = [];
        $modes[] = [ 'OR ROLLBACK' ];
        $modes[] = [ 'OR IGNORE' ];
        $modes[] = [ 'OR ABORT' ];
        $modes[] = [ 'OR REPLACE' ];
        $modes[] = [ 'OR FAIL' ];

        return $modes;
    }

    /**
     * Unit Test Data Provider for Insert modes uppercasing.
     *
     * @return array $expectedmodes Array of insert modes and their expected result
     */
    public function expectedModesProvider(): array
    {
        $expectedmodes   = [];
        $expectedmodes[] = [ 'or rollback', 'OR ROLLBACK' ];
        $expectedmodes[] = [ 'or abort', 'OR ABORT' ];
        $expectedmodes[] = [ 'or ignore', 'OR IGNORE' ];
        $expectedmodes[] = [ 'or replace', 'OR REPLACE' ];
        $expectedmodes[] = [ 'or fail', 'OR FAIL' ];

        return $expectedmodes;
    }

}

?>
