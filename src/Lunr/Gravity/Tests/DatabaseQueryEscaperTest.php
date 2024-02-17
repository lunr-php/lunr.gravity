<?php

/**
 * This file contains the DatabaseQueryEscaperTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2012 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\Tests;

use Lunr\Gravity\DatabaseQueryEscaper;
use Lunr\Halo\LunrBaseTest;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;
use ReflectionClass;
use stdClass;

/**
 * This class contains the tests for the DatabaseQueryEscaper class.
 *
 * @covers Lunr\Gravity\DatabaseQueryEscaper
 */
abstract class DatabaseQueryEscaperTest extends LunrBaseTest
{

    /**
     * Mock instance of a class implementing the DatabaseStringEscaperInterface.
     * @var DatabaseStringEscaperInterface
     */
    protected $escaper;

    /**
     * Instance of the tested class.
     * @var DatabaseQueryEscaper&MockObject&Stub
     */
    protected DatabaseQueryEscaper&MockObject&Stub $class;

    /**
     * Testcase Constructor.
     */
    public function setUp(): void
    {
        $this->escaper = $this->getMockBuilder('Lunr\Gravity\DatabaseStringEscaperInterface')
                              ->getMock();

        $this->class = $this->getMockBuilder('Lunr\Gravity\DatabaseQueryEscaper')
                            ->setConstructorArgs([ $this->escaper ])
                            ->getMockForAbstractClass();

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
     * Unit test data provider for column names.
     *
     * @return array $cols Array of column names and expected escaped values.
     */
    public function columnNameProvider(): array
    {
        $cols   = [];
        $cols[] = [ '*', '*' ];
        $cols[] = [ 'table.*', '`table`.*' ];
        $cols[] = [ 'col', '`col`' ];
        $cols[] = [ 'table.col', '`table`.`col`' ];
        $cols[] = [ 'db.table.col', '`db`.`table`.`col`' ];

        return $cols;
    }

    /**
     * Unit test data provider for table names.
     *
     * @return array $cols Array of table names and expected escaped values.
     */
    public function tableNameProvider(): array
    {
        $cols   = [];
        $cols[] = [ 'table', '`table`' ];
        $cols[] = [ 'db.table', '`db`.`table`' ];

        return $cols;
    }

    /**
     * Unit Test Data Provider for legal input values to be escaped as integer.
     *
     *  @return array $expecteds array of value to be escaped and their result
     */
    public function expectedIntegerProvider(): array
    {
        $expecteds   = [];
        $expecteds[] = [ '1', 1 ];
        $expecteds[] = [ '10', 10 ];
        $expecteds[] = [ '37', 37 ];

        return $expecteds;
    }

    /**
     * Unit Test Data Provider for legal input values to be escaped as integer.
     *
     *  @return array $expecteds array of value to be escaped and their result
     */
    public function expectedFloatProvider(): array
    {
        $expecteds   = [];
        $expecteds[] = [ '1.0', 1 ];
        $expecteds[] = [ '10.1', 10.1 ];

        return $expecteds;
    }

    /**
     * Unit Test Data Provider for illegalinput values to be escaped as integer.
     *
     *  @return array $illegals array of value to be escaped and their result
     */
    public function illegalIntegerProvider(): array
    {
        $illegals   = [];
        $illegals[] = [ 3.3, 3 ];

        $illegals[] = [ NULL, 0 ];

        $illegals[] = [ FALSE, 0 ];
        $illegals[] = [ TRUE, 1 ];

        $illegals[] = [ 'value', 0 ];
        $illegals[] = [ '1x10', 1 ];

        $illegals[] = [ [], 0 ];
        $illegals[] = [ [ 'a', 'b' ], 1 ];

        return $illegals;
    }

    /**
     * Unit Test Data Provider for illegalinput values to be escaped as float.
     *
     *  @return array $illegals array of value to be escaped and their result
     */
    public function illegalFloatProvider(): array
    {
        $illegals   = [];
        $illegals[] = [ '3.3.3', 3.3 ];

        $illegals[] = [ NULL, 0 ];

        $illegals[] = [ FALSE, 0 ];
        $illegals[] = [ TRUE, 1 ];

        $illegals[] = [ 'value', 0 ];

        $illegals[] = [ [], 0 ];
        $illegals[] = [ [ 'a', 'b' ], 1 ];

        return $illegals;
    }

}

?>
