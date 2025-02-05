<?php

/**
 * This file contains the MySQLSimpleDMLQueryBuilderTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2013 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MySQL\Tests;

use Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder;
use Lunr\Halo\LunrBaseTestCase;

/**
 * This class contains common setup routines, providers
 * and shared attributes for testing the MySQLSimpleDMLQueryBuilder class.
 *
 * @covers Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder
 */
abstract class MySQLSimpleDMLQueryBuilderTest extends LunrBaseTestCase
{

    /**
     * Mock instance of the MySQLQueryEscaper class.
     * @var MySQLQueryEscaper
     */
    protected $escaper;

    /**
     * Mock instance of the MySQLDMLQueryBuilder class.
     * @var MySQLDMLQueryBuilder
     */
    protected $builder;

    /**
     * Instance of the tested class.
     * @var MySQLSimpleDMLQueryBuilder
     */
    protected MySQLSimpleDMLQueryBuilder $class;

    /**
     * Testcase Constructor.
     */
    public function setUp(): void
    {
        $this->escaper = $this->getMockBuilder('Lunr\Gravity\MySQL\MySQLQueryEscaper')
                              ->disableOriginalConstructor()
                              ->getMock();

        $this->builder = $this->getMockBuilder('Lunr\Gravity\MySQL\MySQLDMLQueryBuilder')
                              ->getMock();

        $this->class = new MySQLSimpleDMLQueryBuilder($this->builder, $this->escaper);

        parent::baseSetUp($this->class);
    }

    /**
     * Testcase Destructor.
     */
    public function tearDown(): void
    {
        unset($this->escaper);
        unset($this->class);
        unset($this->builder);

        parent::tearDown();
    }

    /**
     * Unit test data provider for location references.
     *
     * @return array $values Array of location reference values.
     */
    public function locationReferenceAliasProvider(): array
    {
        $values   = [];
        $values[] = [ 'table AS t', TRUE, 'table', 't', 'table AS t' ];
        $values[] = [ 'table as t', TRUE, 'table', 't', 'table AS t' ];
        $values[] = [ 'column AS c', FALSE, 'column', 'c', 'column AS c' ];
        $values[] = [ 'column as c', FALSE, 'column', 'c', 'column AS c' ];

        return $values;
    }

    /**
     * Unit test data provider for location references.
     *
     * @return array $values Array of location reference values.
     */
    public function locationReferenceProvider(): array
    {
        $values   = [];
        $values[] = [ 'table', TRUE, 'table' ];
        $values[] = [ 'column', FALSE, 'column' ];

        return $values;
    }

    /**
    * Unit test data provider for tested union operators.
    *
    * @return array $compound operators for union query
    */
    public function unionOperatorProvider(): array
    {
        $operators   = [];
        $operators[] = [ '' ];
        $operators[] = [ 'ALL' ];
        $operators[] = [ 'DISTINCT' ];
        $operators[] = [ TRUE ];
        $operators[] = [ FALSE ];

        return $operators;
    }

}

?>
