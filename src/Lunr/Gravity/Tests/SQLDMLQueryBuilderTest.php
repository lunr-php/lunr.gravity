<?php

/**
 * This file contains the SQLDMLQueryBuilderTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2013 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\Tests;

use Lunr\Gravity\SQLDMLQueryBuilder;
use Lunr\Halo\LunrBaseTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;

/**
 * This class contains common setup routines, providers
 * and shared attributes for testing the SQLDMLQueryBuilder class.
 *
 * @covers Lunr\Gravity\SQLDMLQueryBuilder
 */
abstract class SQLDMLQueryBuilderTest extends LunrBaseTestCase
{

    /**
     * Instance of the tested class.
     * @var SQLDMLQueryBuilder&MockObject&Stub
     */
    protected SQLDMLQueryBuilder&MockObject&Stub $class;

     /**
     * TestCase Constructor.
     */
    public function setUp(): void
    {
        $this->class = $this->getMockBuilder('Lunr\Gravity\SQLDMLQueryBuilder')
                            ->getMockForAbstractClass();

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

}

?>
