<?php

/**
 * This file contains the DefragmentationExceptionBaseTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2025 Framna Netherlands B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\Exceptions\Tests;

/**
 * This class contains tests for the DefragmentationException class.
 *
 * @covers \Lunr\Gravity\Exceptions\DefragmentationException
 */
class DefragmentationExceptionBaseTest extends DefragmentationExceptionTestCase
{

    /**
     * Test that the database error code was set correctly.
     */
    public function testDatabaseErrorCodeSetCorrectly(): void
    {
        $this->assertPropertySame('databaseErrorCode', 1024);
    }

    /**
     * Test that the database error message was set correctly.
     */
    public function testDatabaseErrorMessageSetCorrectly(): void
    {
        $this->assertPropertySame('databaseErrorMessage', "There's an error in your query.");
    }

    /**
     * Test that getDatabaseErrorCode() returns the database error code.
     *
     * @covers \Lunr\Gravity\Exceptions\DefragmentationException::getDatabaseErrorCode
     */
    public function testGetDatabaseErrorCodeReturnsErrorCode(): void
    {
        $this->assertSame(1024, $this->class->getDatabaseErrorCode());
    }

    /**
     * Test that getDatabaseErrorMessage() returns the database error message.
     *
     * @covers \Lunr\Gravity\Exceptions\DefragmentationException::getDatabaseErrorMessage
     */
    public function testGetDatabaseErrorMessageReturnsErrorMessage(): void
    {
        $this->assertSame("There's an error in your query.", $this->class->getDatabaseErrorMessage());
    }

    /**
     * Test that the error message was passed correctly.
     */
    public function testErrorMessagePassedCorrectly(): void
    {
        $this->expectExceptionMessage('Exception Message');

        throw $this->class;
    }

    /**
     * Test that setMessage() changes the error message.
     *
     * @covers \Lunr\Gravity\Exceptions\DefragmentationException::setMessage
     */
    public function testSetMessage(): void
    {
        $this->class->setMessage('New error message!');

        $this->expectExceptionMessage('New error message!');

        throw $this->class;
    }

}

?>
