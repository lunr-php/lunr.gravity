<?php

/**
 * This file contains the MySQLConnectionSetTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2012 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MySQL\Tests;

/**
 * This class contains test for the setters of the MySQLConnection class.
 *
 * @covers Lunr\Gravity\MySQL\MySQLConnection
 */
class MySQLConnectionSetTest extends MySQLConnectionTestCase
{

    /**
     * Sample configuration values.
     * @var array
     */
    protected array $valuesMap;

    /**
     * TestCase Constructor.
     */
    public function setUp(): void
    {
        $this->emptySetUp();

        $this->valuesMap = [
            [ 'rwHost', 'rwHost' ],
            [ 'username', 'username' ],
            [ 'password', 'password' ],
            [ 'database', 'database' ],
            [ 'driver', 'mysql' ],
            [ 'sslKey', 'sslKey' ],
            [ 'sslCert', 'sslCert' ],
            [ 'caCert', 'caCert' ],
            [ 'caPath', 'caPath' ],
            [ 'cipher', 'cipher' ],
        ];
    }

    /**
     * Test that set_configuration sets rwHost correctly.
     *
     * @covers Lunr\Gravity\MySQL\MySQLConnection::set_configuration
     */
    public function testSetConfigurationSetsRWHostCorrectly(): void
    {
        $this->configuration->expects($this->any())
                            ->method('offsetGet')
                            ->willReturnMap($this->valuesMap);

        $property = $this->getReflectionProperty('rwHost');
        $property->setValue($this->class, '');

        $method = $this->getReflectionMethod('set_configuration');
        $method->invoke($this->class);

        $this->assertPropertyEquals('rwHost', $property->getValue($this->class));
    }

    /**
     * Test that set_configuration sets username correctly.
     *
     * @covers Lunr\Gravity\MySQL\MySQLConnection::set_configuration
     */
    public function testSetConfigurationSetsUsernameCorrectly(): void
    {
        $this->configuration->expects($this->any())
                            ->method('offsetGet')
                            ->willReturnMap($this->valuesMap);

        $property = $this->getReflectionProperty('user');
        $property->setValue($this->class, '');

        $method = $this->getReflectionMethod('set_configuration');
        $method->invoke($this->class);

        $this->assertEquals('username', $property->getValue($this->class));
    }

    /**
     * Test that set_configuration sets password correctly.
     *
     * @covers Lunr\Gravity\MySQL\MySQLConnection::set_configuration
     */
    public function testSetConfigurationSetsPasswordCorrectly(): void
    {
        $this->configuration->expects($this->any())
                            ->method('offsetGet')
                            ->willReturnMap($this->valuesMap);

        $property = $this->getReflectionProperty('pwd');
        $property->setValue($this->class, '');

        $method = $this->getReflectionMethod('set_configuration');
        $method->invoke($this->class);

        $this->assertEquals('password', $property->getValue($this->class));
    }

    /**
     * Test that set_configuration sets database correctly.
     *
     * @covers Lunr\Gravity\MySQL\MySQLConnection::set_configuration
     */
    public function testSetConfigurationSetsDatabaseCorrectly(): void
    {
        $this->configuration->expects($this->any())
                            ->method('offsetGet')
                            ->willReturnMap($this->valuesMap);

        $property = $this->getReflectionProperty('db');
        $property->setValue($this->class, '');

        $method = $this->getReflectionMethod('set_configuration');
        $method->invoke($this->class);

        $this->assertEquals('database', $property->getValue($this->class));
    }

    /**
     * Test that set_configuration sets roHost to rwHost if it is not set.
     *
     * @covers Lunr\Gravity\MySQL\MySQLConnection::set_configuration
     */
    public function testSetConfigurationSetsROHostToRWHostIfItIsNotSet(): void
    {
        $this->configuration->expects($this->any())
                            ->method('offsetGet')
                            ->willReturnMap($this->valuesMap);

        $property = $this->getReflectionProperty('roHost');
        $property->setValue($this->class, '');

        $method = $this->getReflectionMethod('set_configuration');
        $method->invoke($this->class);

        $this->assertEquals('rwHost', $property->getValue($this->class));
    }

    /**
     * Test that set_configuration sets roHost to rwHost if it is empty.
     *
     * @covers Lunr\Gravity\MySQL\MySQLConnection::set_configuration
     */
    public function testSetConfigurationSetsROHostToRWHostIfItIsEmpty(): void
    {
        $this->valuesMap[] = [ 'roHost', '' ];

        $this->configuration->expects($this->any())
                            ->method('offsetGet')
                            ->willReturnMap($this->valuesMap);

        $property = $this->getReflectionProperty('roHost');
        $property->setValue($this->class, '');

        $method = $this->getReflectionMethod('set_configuration');
        $method->invoke($this->class);

        $this->assertEquals('rwHost', $property->getValue($this->class));
    }

    /**
     * Test that set_configuration sets roHost correctly.
     *
     * @covers Lunr\Gravity\MySQL\MySQLConnection::set_configuration
     */
    public function testSetConfigurationSetsROHostCorrectly(): void
    {
        $this->valuesMap[] = [ 'roHost', 'roHost' ];

        $this->configuration->expects($this->any())
                            ->method('offsetGet')
                            ->willReturnMap($this->valuesMap);

        $this->configuration->expects($this->any())
                            ->method('offsetExists')
                            ->willReturn(TRUE);

        $property = $this->getReflectionProperty('roHost');
        $property->setValue($this->class, '');

        $method = $this->getReflectionMethod('set_configuration');
        $method->invoke($this->class);

        $this->assertEquals('roHost', $property->getValue($this->class));
    }

    /**
     * Test that set_configuration sets port to ini-value if not set.
     *
     * @covers Lunr\Gravity\MySQL\MySQLConnection::set_configuration
     */
    public function testSetConfigurationSetsPortToIniValueIfNotSet(): void
    {
        $this->configuration->expects($this->any())
                            ->method('offsetGet')
                            ->willReturnMap($this->valuesMap);

        $this->setReflectionPropertyValue('port', 0);

        $method = $this->getReflectionMethod('set_configuration');
        $method->invoke($this->class);

        $this->assertPropertyEquals('port', ini_get('mysqli.default_port'));
    }

    /**
     * Test that set_configuration sets port to a hardcoded value of fetching from php.ini fails.
     *
     * @covers Lunr\Gravity\MySQL\MySQLConnection::set_configuration
     */
    public function testSetConfigurationSetsPortToHardcodedValueIfIniFails(): void
    {
        $this->mockFunction('ini_get', fn($arg) => FALSE);

        $this->configuration->expects($this->any())
                            ->method('offsetGet')
                            ->willReturnMap($this->valuesMap);

        $this->setReflectionPropertyValue('port', 0);

        $method = $this->getReflectionMethod('set_configuration');
        $method->invoke($this->class);

        $this->assertPropertyEquals('port', 3306);

        $this->unmockFunction('ini_get');
    }

    /**
     * Test that set_configuration sets port correctly.
     *
     * @covers Lunr\Gravity\MySQL\MySQLConnection::set_configuration
     */
    public function testSetConfigurationSetsPortCorrectly(): void
    {
        $this->valuesMap[] = [ 'port', 20 ];

        $this->configuration->expects($this->any())
                            ->method('offsetExists')
                            ->willReturn(TRUE);

        $this->configuration->expects($this->any())
                            ->method('offsetGet')
                            ->willReturnMap($this->valuesMap);

        $this->setReflectionPropertyValue('port', 0);

        $method = $this->getReflectionMethod('set_configuration');
        $method->invoke($this->class);

        $this->assertPropertyEquals('port', 20);
    }

    /**
     * Test that set_configuration sets socket to ini-value if not set.
     *
     * @covers Lunr\Gravity\MySQL\MySQLConnection::set_configuration
     */
    public function testSetConfigurationSetsSocketToIniValueIfNotSet(): void
    {
        $this->configuration->expects($this->any())
                            ->method('offsetGet')
                            ->willReturnMap($this->valuesMap);

        $property = $this->getReflectionProperty('socket');
        $property->setValue($this->class, '');

        $method = $this->getReflectionMethod('set_configuration');
        $method->invoke($this->class);

        $this->assertEquals(ini_get('mysqli.default_socket'), $property->getValue($this->class));
    }

    /**
     * Test that set_configuration sets socket correctly.
     *
     * @covers Lunr\Gravity\MySQL\MySQLConnection::set_configuration
     */
    public function testSetConfigurationSetsSocketCorrectly(): void
    {
        $this->valuesMap[] = [ 'socket', 'socket' ];

        $this->configuration->expects($this->any())
                            ->method('offsetExists')
                            ->willReturn(TRUE);

        $this->configuration->expects($this->any())
                            ->method('offsetGet')
                            ->willReturnMap($this->valuesMap);

        $property = $this->getReflectionProperty('socket');
        $property->setValue($this->class, '');

        $method = $this->getReflectionMethod('set_configuration');
        $method->invoke($this->class);

        $this->assertEquals('socket', $property->getValue($this->class));
    }

    /**
     * Test that set_configuration sets sslKey correctly.
     *
     * @covers Lunr\Gravity\MySQL\MySQLConnection::set_configuration
     */
    public function testSetConfigurationSetsSSLKeyCorrectly(): void
    {
        $this->configuration->expects($this->any())
                            ->method('offsetExists')
                            ->willReturn(TRUE);

        $this->configuration->expects($this->any())
                            ->method('offsetGet')
                            ->willReturnMap($this->valuesMap);

        $property = $this->getReflectionProperty('sslKey');
        $property->setValue($this->class, '');

        $method = $this->getReflectionMethod('set_configuration');
        $method->invoke($this->class);

        $this->assertEquals('sslKey', $property->getValue($this->class));
    }

    /**
     * Test that set_configuration sets sslCert correctly.
     *
     * @covers Lunr\Gravity\MySQL\MySQLConnection::set_configuration
     */
    public function testSetConfigurationSetsSSLCertCorrectly(): void
    {
        $this->configuration->expects($this->any())
                            ->method('offsetExists')
                            ->willReturn(TRUE);

        $this->configuration->expects($this->any())
                            ->method('offsetGet')
                            ->willReturnMap($this->valuesMap);

        $property = $this->getReflectionProperty('sslCert');
        $property->setValue($this->class, '');

        $method = $this->getReflectionMethod('set_configuration');
        $method->invoke($this->class);

        $this->assertEquals('sslCert', $property->getValue($this->class));
    }

    /**
     * Test that set_configuration sets caCert correctly.
     *
     * @covers Lunr\Gravity\MySQL\MySQLConnection::set_configuration
     */
    public function testSetConfigurationSetsCACertCorrectly(): void
    {
        $this->configuration->expects($this->any())
                            ->method('offsetExists')
                            ->willReturn(TRUE);

        $this->configuration->expects($this->any())
                            ->method('offsetGet')
                            ->willReturnMap($this->valuesMap);

        $property = $this->getReflectionProperty('caCert');
        $property->setValue($this->class, '');

        $method = $this->getReflectionMethod('set_configuration');
        $method->invoke($this->class);

        $this->assertEquals('caCert', $property->getValue($this->class));
    }

    /**
     * Test that set_configuration sets caPath correctly.
     *
     * @covers Lunr\Gravity\MySQL\MySQLConnection::set_configuration
     */
    public function testSetConfigurationSetsCAPathCorrectly(): void
    {
        $this->configuration->expects($this->any())
                            ->method('offsetExists')
                            ->willReturn(TRUE);

        $this->configuration->expects($this->any())
                            ->method('offsetGet')
                            ->willReturnMap($this->valuesMap);

        $property = $this->getReflectionProperty('caPath');
        $property->setValue($this->class, '');

        $method = $this->getReflectionMethod('set_configuration');
        $method->invoke($this->class);

        $this->assertEquals('caPath', $property->getValue($this->class));
    }

    /**
     * Test that set_configuration sets cipher correctly.
     *
     * @covers Lunr\Gravity\MySQL\MySQLConnection::set_configuration
     */
    public function testSetConfigurationSetsCipherCorrectly(): void
    {
        $this->configuration->expects($this->any())
                            ->method('offsetExists')
                            ->willReturn(TRUE);

        $this->configuration->expects($this->any())
                            ->method('offsetGet')
                            ->willReturnMap($this->valuesMap);

        $property = $this->getReflectionProperty('cipher');
        $property->setValue($this->class, '');

        $method = $this->getReflectionMethod('set_configuration');
        $method->invoke($this->class);

        $this->assertEquals('cipher', $property->getValue($this->class));
    }

}

?>
