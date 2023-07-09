<?php

/**
 * MySQL access class.
 *
 * SPDX-FileCopyrightText: Copyright 2023 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MySQL;

use Lunr\Gravity\Exceptions\DeadlockException;
use Lunr\Gravity\Exceptions\LockTimeoutException;
use Lunr\Gravity\Exceptions\QueryException;
use Lunr\Gravity\DatabaseAccessObject;
use Psr\Log\LoggerInterface;

/**
 * This class provides a way to access databases.
 */
abstract class MySQLAccessObject extends DatabaseAccessObject
{

    /**
     * MySQL connection handler.
     * @var MySQLConnection
     */
    protected MySQLConnection $db;

    /**
     * Query Escaper for the main connection.
     * @var MySQLQueryEscaper
     */
    protected MySQLQueryEscaper $escaper;

    /**
     * Constructor.
     *
     * @param MySQLConnection $connection Shared instance of a database connection class
     * @param LoggerInterface $logger     Shared instance of a Logger class
     */
    public function __construct(MySQLConnection $connection, LoggerInterface $logger)
    {
        parent::__construct($connection, $logger);

        $this->db      = $connection;
        $this->escaper = $this->db->get_query_escaper_object();
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        unset($this->escaper);
        unset($this->db);

        parent::__destruct();
    }

    /**
     * Swap the currently used database connection with a new one.
     *
     * @param MySQLConnection $connection Shared instance of a database connection class
     *
     * @return void
     */
    public function swap_connection(MySQLConnection $connection): void
    {
        $this->db      = $connection;
        $this->escaper = $this->db->get_query_escaper_object();

        parent::swap_generic_connection($connection);
    }

}

?>
