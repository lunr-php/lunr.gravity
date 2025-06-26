<?php

/**
 * This file contains the DefragmentationException class.
 *
 * SPDX-FileCopyrightText: Copyright 2023 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\Exceptions;

/**
 * Exception for a database table optimization failure.
 */
class DefragmentationException extends DatabaseException
{

    /**
     * Numerical error code for the error from the database system.
     * @var int
     */
    private int $databaseErrorCode;

    /**
     * Error message from the database system.
     * @var string
     */
    private string $databaseErrorMessage;

    /**
     * Constructor.
     *
     * @param int    $databaseErrorCode    Numerical error code for the error from the database system
     * @param string $databaseErrorMessage Error message from the database system
     * @param string $message              The exception message
     */
    public function __construct(int $databaseErrorCode, string $databaseErrorMessage, string $message = 'Defragmentation Exception!')
    {
        $this->databaseErrorCode    = $databaseErrorCode;
        $this->databaseErrorMessage = $databaseErrorMessage;

        parent::__construct($message);
    }

    /**
     * Set a more specific error message for the exception.
     *
     * @param string $message Error message
     *
     * @return void
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * Gets the database error code for the error.
     *
     * @return int Error code
     */
    final public function getDatabaseErrorCode(): int
    {
        return $this->databaseErrorCode;
    }

    /**
     * Gets the database error message for the error.
     *
     * @return string Error message
     */
    final public function getDatabaseErrorMessage(): string
    {
        return $this->databaseErrorMessage;
    }

}

?>
