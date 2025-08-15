<?php

/**
 * This file contains the MySQLConnectionQueryTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2012 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MySQL\Tests;

use Lunr\Ticks\AnalyticsDetailLevel;
use MySQLi_Result;

/**
 * This class contains query related unit tests for MySQLConnection.
 *
 * @covers Lunr\Gravity\MySQL\MySQLConnection
 */
class MySQLConnectionQueryTest extends MySQLConnectionTestCase
{

    /**
     * Test that query() throws an exception when not connected.
     *
     * @covers Lunr\Gravity\MySQL\MySQLConnection::query
     */
    public function testQueryThrowsExceptionWhenNotConnected(): void
    {
        $mysqli = new MockMySQLiFailedConnection($this->getMockBuilder('\mysqli')->getMock());

        $this->setReflectionPropertyValue('mysqli', $mysqli);

        $this->expectException('Lunr\Gravity\Exceptions\ConnectionException');
        $this->expectExceptionMessage('Could not establish connection to the database!');

        $this->logger->expects('debug')->never();

        $this->class->query('query');
    }

    /**
     * Test that query() returns a QueryResult when connected.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\MySQL\MySQLConnection::query
     */
    public function testQueryReturnsQueryResultWhenConnected(): void
    {
        $mysqli = new MockMySQLiSuccessfulConnection($this->getMockBuilder('\mysqli')->getMock());

        $this->setReflectionPropertyValue('mysqli', $mysqli);
        $this->setReflectionPropertyValue('connected', TRUE);

        $mysqli->expects($this->once())
               ->method('query')
               ->willReturn(TRUE);

        $this->mockFunction('mysqli_affected_rows', fn() => 0);
        $this->mockFunction('microtime', function () { return 1; });

        $this->logger->expects('debug')
                     ->once()
                     ->with('query: {query}', [ 'query' => 'query' ]);

        $this->logger->expects('debug')
                     ->once()
                     ->with('Query executed in 0 seconds');

        $query = $this->class->query('query');

        $this->unmockFunction('mysqli_affected_rows');
        $this->unmockFunction('microtime');

        $this->assertInstanceOf('Lunr\Gravity\MySQL\MySQLQueryResult', $query);
        $this->assertFalse($query->has_failed());
    }

    /**
     * Test that query() prepends the SQL query with a query hint if one is set.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\MySQL\MySQLConnection::query
     */
    public function testQueryPrependsQueryHintIfPresent(): void
    {
        $mysqli = new MockMySQLiSuccessfulConnection($this->getMockBuilder('\mysqli')->getMock());

        $this->setReflectionPropertyValue('mysqli', $mysqli);
        $this->setReflectionPropertyValue('connected', TRUE);
        $this->setReflectionPropertyValue('queryHint', '/*hint*/');

        $mysqli->expects($this->once())
               ->method('query')
               ->with('/*hint*/query')
               ->willReturn(TRUE);

        $this->mockFunction('mysqli_affected_rows', fn() => 0);
        $this->mockFunction('microtime', function () { return 1; });

        $this->logger->expects('debug')
                     ->once()
                     ->with('Query executed in 0 seconds');

        $this->logger->expects('debug')
                     ->once()
                     ->with('query: {query}', [ 'query' => '/*hint*/query' ]);

        $query = $this->class->query('query');

        $this->unmockFunction('mysqli_affected_rows');
        $this->unmockFunction('microtime');

        $this->assertEquals('/*hint*/query', $query->query());
    }

    /**
     * Test that query() returns a QueryResult when connected.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\MySQL\MySQLConnection::query
     */
    public function testQueryResetsQueryHint(): void
    {
        $mysqli = new MockMySQLiSuccessfulConnection($this->getMockBuilder('\mysqli')->getMock());

        $this->setReflectionPropertyValue('mysqli', $mysqli);
        $this->setReflectionPropertyValue('connected', TRUE);

        $hint = $this->getReflectionProperty('queryHint');
        $hint->setValue($this->class, '/*hint*/');

        $mysqli->expects($this->once())
               ->method('query')
               ->with('/*hint*/query')
               ->willReturn(TRUE);

        $this->mockFunction('mysqli_affected_rows', fn() => 0);
        $this->mockFunction('microtime', function () { return 1; });

        $this->logger->expects('debug')
                     ->once()
                     ->with('query: {query}', [ 'query' => '/*hint*/query' ]);

        $this->logger->expects('debug')
                     ->once()
                     ->with('Query executed in 0 seconds');

        $this->class->query('query');

        $this->unmockFunction('mysqli_affected_rows');
        $this->unmockFunction('microtime');

        $this->assertSame('', $hint->getValue($this->class));
    }

    /**
     * Test that query() returns a QueryResult when connected.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\MySQL\MySQLConnection::query
     */
    public function testQueryRecordsAnalyticsWithDetailLevelInfo(): void
    {
        $mysqli = new MockMySQLiSuccessfulConnection($this->getMockBuilder('\mysqli')->getMock());

        $this->setReflectionPropertyValue('mysqli', $mysqli);
        $this->setReflectionPropertyValue('connected', TRUE);

        $this->class->enableAnalytics($this->eventLogger, $this->controller, AnalyticsDetailLevel::Info);

        $this->controller->shouldReceive('startChildSpan')
                         ->once();

        $traceID      = '7b333e15-aa78-4957-a402-731aecbb358e';
        $spanID       = '24ec5f90-7458-4dd5-bb51-7a1e8f4baafe';
        $parentSpanID = '8b1f87b5-8383-4413-a341-7619cd4b9948';

        $this->controller->shouldReceive('getTraceId')
                         ->once()
                         ->andReturn($traceID);

        $this->controller->shouldReceive('getSpanId')
                         ->once()
                         ->andReturn($spanID);

        $this->controller->shouldReceive('getParentSpanId')
                         ->once()
                         ->andReturn($parentSpanID);

        $this->controller->shouldReceive('getSpanSpecifictags')
                         ->once()
                         ->andReturn([ 'call' => 'controller/method' ]);

        $this->controller->shouldReceive('stopChildSpan')
                         ->once();

        $this->eventLogger->expects($this->once())
                          ->method('newEvent')
                          ->with('mysql_query_log')
                          ->willReturn($this->event);

        $this->event->expects($this->once())
                    ->method('recordTimestamp');

        $this->event->expects($this->once())
                    ->method('addTags')
                    ->with([
                        'digest'       => '7cd9148ec5a552dbf68de5a6debcf8e4d974db72',
                        'databaseHost' => 'db-server',
                        'successful'   => TRUE,
                        'errorNumber'  => 0,
                        'call'         => 'controller/method',
                    ]);

        $this->event->expects($this->once())
                    ->method('addFields')
                    ->with([
                        'startTimestamp' => 1734352683.3516,
                        'endTimestamp'   => 1734352683.3516,
                        'executionTime'  => 0.0,
                        'traceID'        => $traceID,
                        'spanID'         => $spanID,
                        'parentSpanID'   => $parentSpanID,
                        'canonicalQuery' => 'query',
                        'numberOfRows'   => 0,
                        'errorMessage'   => NULL,
                        'warnings'       => NULL,
                    ]);

        $this->event->expects($this->once())
                    ->method('record');

        $profilingHint = "/* traceID=$traceID,spanID=$spanID */ ";

        $result = $this->getMockBuilder(MySQLi_Result::class)
                       ->disableOriginalConstructor()
                       ->getMock();

        $result->expects($this->once())
               ->method('fetch_row')
               ->willReturn([ 'db-server' ]);

        $mysqli->expects($this->exactly(2))
               ->method('query')
               ->willReturnOnConsecutiveCalls(TRUE, $result);

        $floatval  = 1734352683.3516;
        $stringval = '0.35160200 1734352683';

        $this->mockFunction('microtime', fn(bool $float) => $float ? $floatval : $stringval);
        $this->mockFunction('mysqli_affected_rows', fn() => 0);

        $this->logger->expects('debug')
                     ->once()
                     ->with( 'query: {query}', [ 'query' => $profilingHint . 'query' ]);

        $this->logger->expects('debug')
                     ->once()
                     ->with('Query executed in 0 seconds');

        $query = $this->class->query('query');

        $this->unmockFunction('mysqli_affected_rows');
        $this->unmockFunction('microtime');

        $this->assertInstanceOf('Lunr\Gravity\MySQL\MySQLQueryResult', $query);
        $this->assertFalse($query->has_failed());
    }

    /**
     * Test that query() returns a QueryResult when connected.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\MySQL\MySQLConnection::query
     */
    public function testQueryRecordsAnalyticsWithDetailLevelFull(): void
    {
        $mysqli = new MockMySQLiSuccessfulConnection($this->getMockBuilder('\mysqli')->getMock());

        $this->setReflectionPropertyValue('mysqli', $mysqli);
        $this->setReflectionPropertyValue('connected', TRUE);

        $this->class->enableAnalytics($this->eventLogger, $this->controller, AnalyticsDetailLevel::Full);

        $this->controller->shouldReceive('startChildSpan')
                         ->once();

        $traceID      = '7b333e15-aa78-4957-a402-731aecbb358e';
        $spanID       = '24ec5f90-7458-4dd5-bb51-7a1e8f4baafe';
        $parentSpanID = '8b1f87b5-8383-4413-a341-7619cd4b9948';

        $profilingHint = "/* traceID=$traceID,spanID=$spanID */ ";

        $this->controller->shouldReceive('getTraceId')
                         ->once()
                         ->andReturn($traceID);

        $this->controller->shouldReceive('getSpanId')
                         ->once()
                         ->andReturn($spanID);

        $this->controller->shouldReceive('getParentSpanId')
                         ->once()
                         ->andReturn($parentSpanID);

        $this->controller->shouldReceive('getSpanSpecifictags')
                         ->once()
                         ->andReturn([ 'call' => 'controller/method' ]);

        $this->controller->shouldReceive('stopChildSpan')
                         ->once();

        $this->eventLogger->expects($this->once())
                          ->method('newEvent')
                          ->with('mysql_query_log')
                          ->willReturn($this->event);

        $this->event->expects($this->once())
                    ->method('recordTimestamp');

        $this->event->expects($this->once())
                    ->method('addTags')
                    ->with([
                        'digest'       => '7cd9148ec5a552dbf68de5a6debcf8e4d974db72',
                        'databaseHost' => 'db-server',
                        'successful'   => TRUE,
                        'errorNumber'  => 0,
                        'call'         => 'controller/method',
                    ]);

        $this->event->expects($this->once())
                    ->method('addFields')
                    ->with([
                        'startTimestamp' => 1734352683.3516,
                        'endTimestamp'   => 1734352683.3516,
                        'executionTime'  => 0.0,
                        'traceID'        => $traceID,
                        'spanID'         => $spanID,
                        'parentSpanID'   => $parentSpanID,
                        'canonicalQuery' => 'query',
                        'numberOfRows'   => 0,
                        'errorMessage'   => NULL,
                        'warnings'       => NULL,
                        'query'          => $profilingHint . 'query',
                    ]);

        $this->event->expects($this->once())
                    ->method('record');

        $result = $this->getMockBuilder(MySQLi_Result::class)
                       ->disableOriginalConstructor()
                       ->getMock();

        $result->expects($this->once())
               ->method('fetch_row')
               ->willReturn([ 'db-server' ]);

        $mysqli->expects($this->exactly(2))
               ->method('query')
               ->willReturnOnConsecutiveCalls(TRUE, $result);

        $floatval  = 1734352683.3516;
        $stringval = '0.35160200 1734352683';

        $this->mockFunction('microtime', fn(bool $float) => $float ? $floatval : $stringval);
        $this->mockFunction('mysqli_affected_rows', fn() => 0);

        $this->logger->expects('debug')
                     ->once()
                     ->with( 'query: {query}', [ 'query' => $profilingHint . 'query' ]);

        $this->logger->expects('debug')
                     ->once()
                     ->with( 'Query executed in 0 seconds');

        $query = $this->class->query('query');

        $this->unmockFunction('mysqli_affected_rows');
        $this->unmockFunction('microtime');

        $this->assertInstanceOf('Lunr\Gravity\MySQL\MySQLQueryResult', $query);
        $this->assertFalse($query->has_failed());
    }

    /**
     * Test that async_query() throws an exception when not connected.
     *
     * @covers Lunr\Gravity\MySQL\MySQLConnection::async_query
     */
    public function testAsyncQueryThrowsExceptionWhenNotConnected(): void
    {
        $mysqli = new MockMySQLiFailedConnection($this->getMockBuilder('\mysqli')->getMock());

        $this->setReflectionPropertyValue('mysqli', $mysqli);

        $this->expectException('Lunr\Gravity\Exceptions\ConnectionException');
        $this->expectExceptionMessage('Could not establish connection to the database!');

        $this->logger->expects('debug')
                     ->never();

        $this->class->async_query('query');
    }

    /**
     * Test that async_query() returns a AsyncQueryResult when connected.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\MySQL\MySQLConnection::async_query
     */
    public function testAsyncQueryReturnsQueryResultWhenConnected(): void
    {
        $this->mockFunction('mysqli_affected_rows', fn() => 0);

        $mysqli = new MockMySQLiSuccessfulConnection($this->getMockBuilder('\mysqli')->getMock());

        $this->setReflectionPropertyValue('mysqli', $mysqli);

        $property = $this->getReflectionProperty('connected');
        $property->setValue($this->class, TRUE);

        $mysqli->expects($this->once())
               ->method('query');

        $mysqli->expects($this->once())
               ->method('reap_async_query')
               ->willReturn(TRUE);

        $this->logger->expects('debug')
                     ->once()
                     ->with('query: {query}', [ 'query' => 'query' ]);

        $query = $this->class->async_query('query');

        $this->assertInstanceOf('Lunr\Gravity\MySQL\MySQLAsyncQueryResult', $query);
        $this->assertFalse($query->has_failed());

        $property->setValue($this->class, FALSE);

        $this->unmockFunction('mysqli_affected_rows');
    }

    /**
     * Test that async_query() prepends the SQL query with a query hint if one is set.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\MySQL\MySQLConnection::async_query
     */
    public function testAsyncQueryPrependsQueryHintIfPresent(): void
    {
        $mysqli = new MockMySQLiSuccessfulConnection($this->getMockBuilder('\mysqli')->getMock());

        $this->setReflectionPropertyValue('mysqli', $mysqli);
        $this->setReflectionPropertyValue('connected', TRUE);
        $this->setReflectionPropertyValue('queryHint', '/*hint*/');

        $mysqli->expects($this->once())
               ->method('query')
               ->with('/*hint*/query', MYSQLI_ASYNC);

        $this->logger->expects('debug')
                     ->once()
                     ->with('query: {query}', [ 'query' => '/*hint*/query' ]);

        $query = $this->class->async_query('query');

        $this->assertEquals('/*hint*/query', $query->query());
    }

    /**
     * Test that async_query() returns a QueryResult when connected.
     *
     * @requires extension mysqli
     * @covers   Lunr\Gravity\MySQL\MySQLConnection::async_query
     */
    public function testAsyncQueryResetsQueryHint(): void
    {
        $mysqli = new MockMySQLiSuccessfulConnection($this->getMockBuilder('\mysqli')->getMock());

        $this->setReflectionPropertyValue('mysqli', $mysqli);
        $this->setReflectionPropertyValue('connected', TRUE);

        $hint = $this->getReflectionProperty('queryHint');
        $hint->setValue($this->class, '/*hint*/');

        $mysqli->expects($this->once())
               ->method('query')
               ->with('/*hint*/query', MYSQLI_ASYNC);

        $this->logger->expects('debug')
                     ->once()
                     ->with('query: {query}', [ 'query' => '/*hint*/query' ]);

        $this->class->async_query('query');

        $this->assertSame('', $hint->getValue($this->class));
    }

}

?>
