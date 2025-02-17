<?php

/**
 * This file contains the MockMysqlndFailedConnection class.
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
class MockMySQLndFailedConnection extends MySQLndUhConnection
{

    /**
     * Fake a failed connection to the database server.
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
     * Return a fake error number.
     *
     * @param mysqlnd_connection $connection Mysqlnd connection handle
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @return int $return Fake error number
     */
    public function getErrorNumber($connection)
    {
        return 666;
    }

    /**
     * Assume failed query.
     *
     * @param mysqlnd_connection $connection Mysqlnd connection handle
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @return int $return Assume failed query
     */
    public function reapQuery($connection)
    {
        return FALSE;
    }

}

?>
