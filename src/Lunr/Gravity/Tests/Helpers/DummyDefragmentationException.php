<?php

/**
 * This file contains the DummyDefragmentationException class.
 *
 * SPDX-FileCopyrightText: Copyright 2025 Framna Netherlands B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Helpers;

use Lunr\Gravity\Exceptions\DatabaseException;
use Lunr\Gravity\Exceptions\DefragmentationException;

/**
 * Dummy exception to test a database defragmentation error.
 */
class DummyDefragmentationException extends DefragmentationException
{

    /**
     * Constructor.
     *
     * @param string $message The exception message
     */
    public function __construct(string $message = 'Dummy Defragmentation error!')
    {
        // Skip over the parent constructor to avoid having to specify anything other than the message
        DatabaseException::__construct($message);
    }

}

?>
