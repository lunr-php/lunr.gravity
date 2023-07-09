<?php

/**
 * Database access object interface for transaction support methods.
 *
 * SPDX-FileCopyrightText: Copyright 2023 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity;

/**
 * This interface defines the query escape primitives.
 */
interface TransactionalDatabaseAccessObjectInterface
{

    /**
     * Begin a transaction.
     *
     * @return void
     */
    public function begin_transaction(): void;

    /**
     * Roll back the changes in a transaction.
     *
     * @return void
     */
    public function rollback_transaction(): void;

    /**
     * Commit the changes in a transaction.
     *
     * @return void
     */
    public function commit_transaction(): void;

    /**
     * End a transaction.
     *
     * @return void
     */
    public function end_transaction(): void;

}

?>
