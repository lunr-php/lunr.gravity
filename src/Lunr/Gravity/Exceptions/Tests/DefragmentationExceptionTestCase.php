<?php

/**
 * This file contains the DefragmentationExceptionTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2025 Framna Netherlands B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\Exceptions\Tests;

use Lunr\Gravity\Exceptions\DefragmentationException;
use Lunr\Halo\LunrBaseTestCase;

/**
 * This class contains common setup routines, providers
 * and shared attributes for testing the DefragmentationException class.
 *
 * @covers \Lunr\Gravity\Exceptions\DefragmentationException
 */
abstract class DefragmentationExceptionTestCase extends LunrBaseTestCase
{

    /**
     * Instance of the tested class.
     * @var DefragmentationException
     */
    protected DefragmentationException $class;

    /**
     * TestCase Constructor.
     */
    public function setUp(): void
    {
        $this->class = new DefragmentationException(1024, "There's an error in your query.", 'Exception Message');

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
