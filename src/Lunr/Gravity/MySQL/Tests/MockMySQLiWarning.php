<?php

/**
 * This file contains the MockMysqliWarning class.
 *
 * SPDX-FileCopyrightText: Copyright 2021 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MySQL\Tests;

/**
 * This class is a Mock for the mysqli_warning class.
 */
class MockMySQLiWarning
{

    /**
     * The MySQL warning message
     * @var string
     */
    public $message;

    /**
     * The MySQL warning sql state
     * @var string
     */
    public $sqlstate;

    /**
     * The MySQL warning error code
     * @var int
     */
    public $errno;

    /**
     * The next MockMySQLiWarning or FALSE if no next warning for the next() method
     * @var MockMySQLiWarning|boolean
     */
    public $nextWarning;

    /**
     * Constructor.
     *
     * @param string                 $message     Message of the warning
     * @param string                 $sqlstate    Sqlstate of the warning
     * @param int                    $errno       Error number of the warning
     * @param MockMySQLiWarning|bool $nextWarning Next warning for the next() method
     */
    public function __construct($message, $sqlstate, $errno, $nextWarning)
    {
        $this->message     = $message;
        $this->sqlstate    = $sqlstate;
        $this->errno       = $errno;
        $this->nextWarning = $nextWarning;
    }

    /**
     * Mock of the next() method of mysqli_warning
     *
     * @return bool Returns TRUE if there is a next warning
     *                 Or FALSE if there is no next warning
     */
    public function next()
    {
        if (!$this->nextWarning)
        {
            return FALSE;
        }

        $this->message     = $this->nextWarning->message;
        $this->sqlstate    = $this->nextWarning->sqlstate;
        $this->errno       = $this->nextWarning->errno;
        $this->nextWarning = $this->nextWarning->nextWarning;

        return TRUE;
    }

}

?>
