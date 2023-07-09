<?php

/**
 * SQLite3 access class.
 *
 * SPDX-FileCopyrightText: Copyright 2023 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\SQLite3;

use Lunr\Gravity\Exceptions\DeadlockException;
use Lunr\Gravity\Exceptions\LockTimeoutException;
use Lunr\Gravity\Exceptions\QueryException;
use Lunr\Gravity\DatabaseAccessObject;
use Psr\Log\LoggerInterface;

/**
 * This class provides a way to access databases.
 */
abstract class SQLite3AccessObject extends DatabaseAccessObject
{

    /**
     * SQLite3 connection handler.
     * @var SQLite3Connection
     */
    protected SQLite3Connection $db;

    /**
     * Query Escaper for the main connection.
     * @var SQLite3QueryEscaper
     */
    protected SQLite3QueryEscaper $escaper;

    /**
     * Constructor.
     *
     * @param SQLite3Connection $connection Shared instance of a database connection class
     * @param LoggerInterface   $logger     Shared instance of a Logger class
     */
    public function __construct(SQLite3Connection $connection, LoggerInterface $logger)
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
     * @param SQLite3Connection $connection Shared instance of a database connection class
     *
     * @return void
     */
    public function swap_connection(SQLite3Connection $connection): void
    {
        $this->db      = $connection;
        $this->escaper = $this->db->get_query_escaper_object();

        parent::swap_generic_connection($connection);
    }

}

?>
