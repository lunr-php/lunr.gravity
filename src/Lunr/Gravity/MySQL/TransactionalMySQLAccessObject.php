<?php

/**
 * This file contains the MySQL access object extended with transaction support methods.
 *
 * SPDX-FileCopyrightText: Copyright 2023 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MySQL;

use Psr\Log\LoggerInterface;

/**
 * TransactionalMySQLAccessObject class.
 */
abstract class TransactionalMySQLAccessObject extends MySQLAccessObject
{

    /**
     * Constructor.
     *
     * @param MySQLConnection $connection Shared instance of a database connection class
     * @param LoggerInterface $logger     Shared instance of a Logger class
     */
    public function __construct(MySQLConnection $connection, LoggerInterface $logger)
    {
        parent::__construct($connection, $logger);
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        parent::__destruct();
    }

    /**
     * Begin a transaction.
     *
     * @return void
     */
    public function begin_transaction(): void
    {
        $this->db->begin_transaction();
    }

    /**
     * Roll back the changes in a transaction.
     *
     * @return void
     */
    public function rollback_transaction(): void
    {
        $this->db->rollback();
    }

    /**
     * Commit the changes in a transaction.
     *
     * @return void
     */
    public function commit_transaction(): void
    {
        $this->db->commit();
    }

    /**
     * End a transaction.
     *
     * @return void
     */
    public function end_transaction(): void
    {
        $this->db->end_transaction();
    }

}

?>
