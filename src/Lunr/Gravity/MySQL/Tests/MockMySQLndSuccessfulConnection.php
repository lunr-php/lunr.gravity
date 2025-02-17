<?php

/**
 * This file contains the MockMysqlndSuccessfulConnection class.
 *
 * SPDX-FileCopyrightText: Copyright 2012 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MySQL\Tests;

use MySQLndUhConnection;

/**
 * This class is a mysqlnd_uh connection handler mocking a successful connection.
 */
class MockMySQLndSuccessfulConnection extends MySQLndUhConnection
{

    /**
     * Fake a successful connection to the database server.
     *
     * @param mysqlnd_connection $connection Mysqlnd connection handle
     * @param string             $host       Hostname or IP address
     * @param string             $user       Username
     * @param string             $password   Password
     * @param string             $database   Database
     * @param int                $port       Port
     * @param string             $socket     Socket
     * @param int                $mysqlFlags Connection options
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @return bool $return Whether the connection was successful or not.
     */
    public function connect($connection, $host, $user, $password, $database, $port, $socket, $mysqlFlags)
    {
        return TRUE;
    }

    /**
     * Return a fake thread ID.
     *
     * @param mysqlnd_connection $connection Mysqlnd connection handle
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @return int $return Fake thread ID
     */
    public function getThreadId($connection)
    {
        return 666;
    }

    /**
     * Return a fake number of affected rows.
     *
     * @param mysqlnd_connection $connection Mysqlnd connection handle
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @return int $return Fake number of affected rows
     */
    public function getAffectedRows($connection)
    {
        return 10;
    }

    /**
     * Fake setting charset.
     *
     * @param mysqlnd_connection $connection Mysqlnd connection handle
     * @param string             $charset    Hostname or IP address
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @return bool $return Whether setting the charset was successful or not.
     */
    public function setCharset($connection, $charset)
    {
        return TRUE;
    }

}

?>
