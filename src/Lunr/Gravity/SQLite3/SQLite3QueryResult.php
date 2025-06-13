<?php

/**
 * Contains SQLite3QueryResult class.
 *
 * SPDX-FileCopyrightText: Copyright 2012 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\SQLite3;

use Lunr\Gravity\DatabaseQueryResultInterface;

/**
 * Class SQLite3QueryResult
 *
 * This class contains methods for the result of a query using SQLite3.
 */
class SQLite3QueryResult implements DatabaseQueryResultInterface
{

    /**
     * The Sqlite3 error code for transaction deadlock.
     * @var int
     */
    private const LOCK_TIMEOUT_ERR_CODE = 5;

    /**
     * The Sqlite3 error code for transaction deadlock.
     * @var int
     */
    private const DEADLOCK_ERR_CODE = 6;

    /**
     * The query string that was executed.
     * @var string
     */
    protected $query;

    /**
     * Return value from sqlite3->query().
     * @var mixed
     */
    protected $result;

    /**
     * Shared instance of the sqlite3 class.
     * @var LunrSQLite3
     */
    protected $sqlite3;

    /**
     * Flag whether the query was successful or not.
     * @var bool
     */
    protected $success;

    /**
     * Flag whether the memory has been freed or not.
     * @var bool
     */
    protected $freed;

    /**
     * Description of the error.
     * @var string
     */
    protected $errorMessage;

    /**
     * Error code.
     * @var int
     */
    protected $errorNumber;

    /**
     * Auto incremented ID generated on last insert.
     * @var mixed
     */
    protected $insertID;

    /**
     * Number of affected rows.
     * @var int
     */
    protected $affectedRows;

    /**
     * Constructor to build the results.
     *
     * @param string      $query   Executed query
     * @param mixed       $result  Query result
     * @param LunrSQLite3 $sqlite3 Instance of the LunrSQLite3 class
     */
    public function __construct($query, $result, $sqlite3)
    {
        if (is_object($result))
        {
            $this->success = TRUE;
            $this->freed   = FALSE;
        }
        else
        {
            $this->success = $result;
            $this->freed   = TRUE;
        }

        $this->result  = $result;
        $this->sqlite3 = $sqlite3;
        $this->query   = $query;

        $this->errorMessage = $sqlite3->lastErrorMsg();
        $this->errorNumber  = $sqlite3->lastErrorCode();
        $this->insertID     = $sqlite3->lastInsertRowID();
        $this->affectedRows = $sqlite3->changes();
    }

    /**
     * Destructor to free the results.
     */
    public function __destruct()
    {
        $this->free_result();

        unset($this->success);
        unset($this->freed);
        unset($this->result);
        unset($this->sqlite3);
        unset($this->query);
        unset($this->errorMessage);
        unset($this->errorNumber);
        unset($this->insertID);
        unset($this->affectedRows);
    }

    /**
     * Free memory associated with a result.
     *
     * @return void
     */
    protected function free_result()
    {
        if ($this->freed !== FALSE)
        {
            return;
        }

        $this->result->finalize();
        $this->freed = TRUE;
    }

    /**
     * Check whether the query has a deadlock or not.
     *
     * @return bool $return TRUE if it failed, FALSE otherwise
     */
    public function has_deadlock()
    {
        return $this->errorNumber == self::DEADLOCK_ERR_CODE;
    }

    /**
     * Check whether the query has a lock timeout or not.
     *
     * @return bool the timeout lock status for the query
     */
    public function has_lock_timeout()
    {
        return $this->errorNumber == self::LOCK_TIMEOUT_ERR_CODE;
    }

    /**
     * Check whether the query has failed or not.
     *
     * @return bool $return TRUE if it failed, FALSE otherwise
     */
    public function has_failed()
    {
        return !$this->success;
    }

    /**
     * Get string description of the error, if there was one.
     *
     * @return string $message Error Message
     */
    public function error_message()
    {
        return $this->errorMessage;
    }

    /**
     * Get numerical error code of the error, if there was one.
     *
     * @return int $code Error Code
     */
    public function error_number()
    {
        return $this->errorNumber;
    }

    /**
     * Gives NULL since there is no way to get warnings form SQLite3
     *
     * @return null NULL Returns NULL warnings
     */
    public function warnings()
    {
        return NULL;
    }

    /**
     * Get auto incremented ID generated on last insert.
     *
     * @return int $id If the number is greater than maximal int value it's a string
     *                 otherwise an int
     */
    public function insert_id()
    {
        return $this->insertID;
    }

    /**
     * Get the executed query.
     *
     * @return string $query The executed query
     */
    public function query()
    {
        return $this->query;
    }

    /**
     * Returns the number of rows affected by the last query.
     *
     * @return int $number Number of rows in the result set.
     */
    public function affected_rows()
    {
        return $this->affectedRows;
    }

    /**
     * Returns the number of rows in the query.
     *
     * @return int $number Number of rows in the result set.
     */
    public function number_of_rows()
    {
        $count = 0;

        if (!is_object($this->result))
        {
            return $count;
        }

        while ($this->result->fetchArray(SQLITE3_ASSOC))
        {
            $count++;
        }

        return $count;
    }

    /**
     * Get the entire result set as an array.
     *
     * @param bool $associative TRUE for returning rows as associative arrays,
     *                          FALSE for returning rows as enumerated arrays
     *
     * @return array $output Result set as array
     */
    public function result_array($associative = TRUE)
    {
        $output = [];

        $returnType = $associative ? SQLITE3_ASSOC : SQLITE3_NUM;

        if (!is_object($this->result))
        {
            return $output;
        }

        while ($row = $this->result->fetchArray($returnType))
        {
            $output[] = $row;
        }

        $this->free_result();

        return $output;
    }

    /**
     * Get the first row of the result set.
     *
     * @return array $output First result row as array
     */
    public function result_row()
    {
        $output = is_object($this->result) ? $this->result->fetchArray(SQLITE3_ASSOC) : [];

        $this->free_result();

        return $output;
    }

    /**
     * Get a specific column of the result set.
     *
     * @param string $column Column or Alias name
     *
     * @return array $output Result column as array
     */
    public function result_column($column)
    {
        $output = [];

        if (!is_object($this->result))
        {
            return $output;
        }

        while ($row = $this->result->fetchArray(SQLITE3_ASSOC))
        {
            $output[] = $row[$column];
        }

        $this->free_result();

        return $output;
    }

    /**
     * Get a specific column of the first row of the result set.
     *
     * @param string $column Column or Alias name
     *
     * @return mixed $output NULL if it does not exist, the value otherwise
     */
    public function result_cell($column)
    {
        if (!is_object($this->result))
        {
            return NULL;
        }

        $line = $this->result->fetchArray(SQLITE3_ASSOC);

        $this->free_result();

        return isset($line[$column]) ? $line[$column] : NULL;
    }

}

?>
