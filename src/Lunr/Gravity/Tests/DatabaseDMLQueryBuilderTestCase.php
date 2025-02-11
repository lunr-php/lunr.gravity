<?php

/**
 * This file contains the DatabaseDMLQueryBuilderTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2012 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\Tests;

use Lunr\Gravity\DatabaseDMLQueryBuilder;
use Lunr\Halo\LunrBaseTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;

/**
 * This class contains common setup routines, providers
 * and shared attributes for testing the DatabaseDMLQueryBuilder class.
 *
 * @covers Lunr\Gravity\DatabaseDMLQueryBuilder
 */
abstract class DatabaseDMLQueryBuilderTestCase extends LunrBaseTestCase
{

    /**
     * Instance of the tested class.
     * @var DatabaseDMLQueryBuilder&MockObject&Stub
     */
    protected DatabaseDMLQueryBuilder&MockObject&Stub $class;

    /**
     * TestCase Constructor.
     */
    public function setUp(): void
    {
        $this->class = $this->getMockForAbstractClass('Lunr\Gravity\DatabaseDMLQueryBuilder');

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
     * Unit test data provider for conditional statements.
     *
     * @return array $variants Array of statement variants
     */
    public function conditionalKeywordProvider(): array
    {
        $variants   = [];
        $variants[] = [ 'WHERE', 'where' ];
        $variants[] = [ 'HAVING', 'having' ];
        $variants[] = [ 'ON', 'join' ];

        return $variants;
    }

    /**
     * Unit test data provider for common join types.
     *
     * @return array $variants Array of join types
     */
    public function commonJoinTypeProvider(): array
    {
        $types   = [];
        $types[] = [ '', 'JOIN' ];
        $types[] = [ 'LEFT', 'LEFT JOIN' ];
        $types[] = [ 'LEFT OUTER', 'LEFT OUTER JOIN' ];
        $types[] = [ 'NATURAL LEFT OUTER', 'NATURAL LEFT OUTER JOIN' ];

        return $types;
    }

    /**
     * Unit test data provider for valid index hints.
     *
     * @return array $hints Array of valid index hints and expected prepared values
     */
    public function validIndexHintProvider(): array
    {
        $hints   = [];
        $hints[] = [ [ 'index_hint' ], ' index_hint' ];
        $hints[] = [ [ 'index_hint', 'index_hint' ], ' index_hint, index_hint' ];
        $hints[] = [ [ NULL ], ' ' ];
        $hints[] = [ [ NULL, NULL ], ' ' ];

        return $hints;
    }

    /**
     * Unit test data provider for invalid index hints.
     *
     * @return array $hints Array of invalid index hints
     */
    public function invalidIndexHintProvider(): array
    {
        $hints   = [];
        $hints[] = [ [] ];
        $hints[] = [ NULL ];

        return $hints;
    }

    /**
    * Unit test data provider for common compound queries.
    *
    * @return array $compound Array of compound types
    */
    public function compoundQueryTypeAndOperatorProvider(): array
    {
        $types   = [];
        $types[] = [ 'UNION' ];
        $types[] = [ 'UNION', 'ALL' ];
        $types[] = [ 'UNION', 'DISTINCT' ];
        $types[] = [ 'INTERSECT' ];
        $types[] = [ 'INTERSECT', 'ALL' ];
        $types[] = [ 'INTERSECT', 'DISTINCT' ];
        $types[] = [ 'EXCEPT' ];
        $types[] = [ 'EXCEPT', 'ALL' ];
        $types[] = [ 'EXCEPT', 'DISTINCT' ];

        return $types;
    }

    /**
    * Unit test data provider for common compound queries.
    *
    * @return array $compound Array of compound types
    */
    public function compoundQueryInvalidTypeAndOperatorProvider(): array
    {
        $types   = [];
        $types[] = [ 'UNION', 'Some Operator' ];
        $types[] = [ 'UNION', 0 ];
        $types[] = [ 'UNION', FALSE ];
        $types[] = [ 'UNION', TRUE ];
        $types[] = [ 'INTERSECT', 'Some Operator' ];
        $types[] = [ 'INTERSECT', 0 ];
        $types[] = [ 'INTERSECT', FALSE ];
        $types[] = [ 'INTERSECT', TRUE ];
        $types[] = [ 'EXCEPT', 'Some Operator' ];
        $types[] = [ 'EXCEPT', 0 ];
        $types[] = [ 'EXCEPT', FALSE ];
        $types[] = [ 'EXCEPT', TRUE ];

        return $types;
    }

    /**
     * Unit Test Data Provider for initial insert values.
     *
     * @return array $values Array of initial insert values.
     */
    public function insertValuesProvider(): array
    {
        $values   = [];
        $values[] = [ [ [ 'value1', 'value2', 'value3' ] ] ];
        $values[] = [ [ 'value1', 'value2', 'value3' ] ];
        $values[] = [ [ 'key1' => 'value1', 'key2' => 'value2', 'key3' => 'value3' ] ];

        return $values;
    }

}

?>
