<?php

/**
 * This file contains the SQLite3ConnectionSetTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2013 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\SQLite3\Tests;

/**
 * This class contains test for the setters of the SQLite3Connection class.
 *
 * @covers Lunr\Gravity\SQLite3\SQLite3Connection
 */
class SQLite3ConnectionSetTest extends SQLite3ConnectionTestCase
{

    /**
     * TestCase Constructor.
     */
    public function setUp(): void
    {
        $this->emptySetUp();
    }

    /**
     * Test that set_configuration sets database correctly.
     *
     * @covers Lunr\Gravity\SQLite3\SQLite3Connection::set_configuration
     */
    public function testSetConfigurationSetsDatabaseCorrectly(): void
    {

        $this->configuration->expects($this->any())
                           ->method('offsetGet')
                           ->with('db')
                           ->willReturn([ 'file' => NULL ]);

        $this->setReflectionPropertyValue('db', '');

        $method = $this->getReflectionMethod('set_configuration');

        $method->invoke($this->class);

        $this->assertEquals(':memory:', $this->getReflectionPropertyValue('db'));
    }

    /**
     * Test that set_configuration doesn't set the database.
     *
     * @covers Lunr\Gravity\SQLite3\SQLite3Connection::set_configuration
     */
    public function testSetConfigurationDoesNotSetDatabase(): void
    {

        $this->configuration->expects($this->any())
                            ->method('offsetGet')
                            ->with('db')
                            ->willReturn([ 'file' => NULL ]);

        $this->setReflectionPropertyValue('db', '');

        $method = $this->getReflectionMethod('set_configuration');

        $method->invoke($this->class);

        $this->assertEquals(':memory:', $this->getReflectionPropertyValue('db'));
    }

}

?>
