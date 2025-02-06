<?php

/**
 * This file contains the MySQLDMLQueryBuilderTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2012 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MySQL\Tests;

use Lunr\Gravity\MySQL\MySQLDMLQueryBuilder;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * This class contains common setup routines, providers
 * and shared attributes for testing the MySQLDMLQueryBuilder class.
 *
 * @covers Lunr\Gravity\MySQL\MySQLDMLQueryBuilder
 */
abstract class MySQLDMLQueryBuilderTestCase extends TestCase
{

    /**
     * Instance of the MySQLQueryBuilder class.
     * @var MySQLDMLQueryBuilder
     */
    protected $builder;

    /**
     * Reflection instance of the MySQLDMLQueryBuilder class.
     * @var ReflectionClass
     */
    protected $builder_reflection;

    /**
     * TestCase Constructor.
     */
    public function setUp(): void
    {

        $this->builder            = new MySQLDMLQueryBuilder();
        $this->builder_reflection = new ReflectionClass('Lunr\Gravity\MySQL\MySQLDMLQueryBuilder');
    }

    /**
     * TestCase Destructor.
     */
    public function tearDown(): void
    {
        unset($this->builder);
        unset($this->builder_reflection);
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
        $modes[] = [ 'DISTINCTROW' ];

        return $modes;
    }

    /**
     * Unit Test Data Provider for Select modes handling the sql query cache.
     *
     * @return array $modes Array of select modes
     */
    public function selectModesCacheProvider(): array
    {
        $modes   = [];
        $modes[] = [ 'SQL_CACHE' ];
        $modes[] = [ 'SQL_NO_CACHE' ];

        return $modes;
    }

    /**
     * Unit Test Data Provider for standard Select modes.
     *
     * @return array $modes Array of select modes
     */
    public function selectModesStandardProvider(): array
    {
        $modes   = [];
        $modes[] = [ 'HIGH_PRIORITY' ];
        $modes[] = [ 'STRAIGHT_JOIN' ];
        $modes[] = [ 'SQL_BIG_RESULT' ];
        $modes[] = [ 'SQL_SMALL_RESULT' ];
        $modes[] = [ 'SQL_BUFFER_RESULT' ];
        $modes[] = [ 'SQL_CALC_FOUND_ROWS' ];

        return $modes;
    }

    /**
     * Unit Test Data Provider for standard Select modes.
     *
     * @return array $modes Array of select modes
     */
    public function updateModesStandardProvider(): array
    {
        $modes   = [];
        $modes[] = [ 'LOW_PRIORITY' ];
        $modes[] = [ 'IGNORE' ];

        return $modes;
    }

    /**
     * Unit Test Data Provider for standard lock modes.
     *
     * @return array $modes Array of lock modes
     */
    public function lockModesStandardProvider(): array
    {
        $modes   = [];
        $modes[] = [ 'FOR UPDATE' ];
        $modes[] = [ 'LOCK IN SHARE MODE' ];

        return $modes;
    }

    /**
     * Unit Test Data Provider for Delete modes.
     *
     * @return array $modes Array of delete modes
     */
    public function deleteModesStandardProvider(): array
    {
        $modes   = [];
        $modes[] = [ 'LOW_PRIORITY' ];
        $modes[] = [ 'QUICK' ];
        $modes[] = [ 'IGNORE' ];

        return $modes;
    }

    /**
     * Unit Test Data Provider for Delete modes uppercasing.
     *
     * @return array $expectedmodes Array of delete modes and their expected result
     */
    public function expectedDeleteModesProvider(): array
    {
        $expectedmodes   = [];
        $expectedmodes[] = [ 'low_priority', 'LOW_PRIORITY' ];
        $expectedmodes[] = [ 'QuIcK', 'QUICK' ];
        $expectedmodes[] = [ 'IGNORE', 'IGNORE' ];

        return $expectedmodes;
    }

    /**
     * Unit Test Data Provider for Insert modes.
     *
     * @return array $modes Array of Insert modes
     */
    public function insertModesStandardProvider(): array
    {
        $modes   = [];
        $modes[] = [ 'LOW_PRIORITY' ];
        $modes[] = [ 'DELAYED' ];
        $modes[] = [ 'HIGH_PRIORITY' ];
        $modes[] = [ 'IGNORE' ];

        return $modes;
    }

    /**
     * Unit Test Data Provider for Insert modes uppercasing.
     *
     * @return array $expectedmodes Array of insert modes and their expected result
     */
    public function expectedInsertModesProvider(): array
    {
        $expectedmodes   = [];
        $expectedmodes[] = [ 'low_priority', 'LOW_PRIORITY' ];
        $expectedmodes[] = [ 'DeLayeD', 'DELAYED' ];
        $expectedmodes[] = [ 'IGNORE', 'IGNORE' ];

        return $expectedmodes;
    }

}

?>
