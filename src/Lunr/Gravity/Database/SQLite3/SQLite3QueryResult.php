<?php

/**
 * Contains SQLite3QueryResult class.
 *
 * PHP Version 5.6
 *
 * @package    Lunr\Gravity\Database\SQLite3
 * @author     Ruben de Groot <r.degroot@m2mobi.com>
 * @copyright  2012-2017, M2Mobi BV, Amsterdam, The Netherlands
 * @license    http://lunr.nl/LICENSE MIT License
 */

namespace Lunr\Gravity\Database\SQLite3;

use Lunr\Gravity\Database\DatabaseQueryResultInterface;

/**
 * Class SQLite3QueryResult
 *
 * This class contains methods for the result of a query using SQLite3.
 */
class SQLite3QueryResult implements DatabaseQueryResultInterface
{

    /**
     * The Sqlite3 error code for transaction deadlock.
     * @var Integer
     */
    const DEADLOCK_ERR_CODE = 6;

    /**
     * The query string that was executed.
     * @var String
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
     * @var Boolean
     */
    protected $success;

    /**
     * Flag whether the memory has been freed or not.
     * @var Boolean
     */
    protected $freed;

    /**
     * Description of the error.
     * @var String
     */
    protected $error_message;

    /**
     * Error code.
     * @var Integer
     */
    protected $error_number;

    /**
     * Auto incremented ID generated on last insert.
     * @var mixed
     */
    protected $insert_id;

    /**
     * Number of affected rows.
     * @var Integer
     */
    protected $affected_rows;

    /**
     * Constructor to build the results.
     *
     * @param String      $query   Executed query
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

        $this->error_message = $sqlite3->lastErrorMsg();
        $this->error_number  = $sqlite3->lastErrorCode();
        $this->insert_id     = $sqlite3->lastInsertRowID();
        $this->affected_rows = $sqlite3->changes();
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
        unset($this->error_message);
        unset($this->error_number);
        unset($this->insert_id);
        unset($this->affected_rows);
    }

    /**
     * Free memory associated with a result.
     *
     * @return void
     */
    protected function free_result()
    {
        if ($this->freed === FALSE)
        {
            $this->result->finalize();
            $this->freed = TRUE;
        }
    }

    /**
     * Check whether the query has a deadlock or not.
     *
     * @return Boolean $return TRUE if it failed, FALSE otherwise
     */
    public function has_deadlock()
    {
        return ($this->error_number == self::DEADLOCK_ERR_CODE);
    }

    /**
     * Check whether the query has failed or not.
     *
     * @return Boolean $return TRUE if it failed, FALSE otherwise
     */
    public function has_failed()
    {
        return !$this->success;
    }

    /**
     * Get string description of the error, if there was one.
     *
     * @return String $message Error Message
     */
    public function error_message()
    {
        return $this->error_message;
    }

    /**
     * Get numerical error code of the error, if there was one.
     *
     * @return Integer $code Error Code
     */
    public function error_number()
    {
        return $this->error_number;
    }

    /**
     * Get auto incremented ID generated on last insert.
     *
     * @return mixed $id If the number is greater than maximal int value it's a String
     *                   otherwise an Integer
     */
    public function insert_id()
    {
        return $this->insert_id;
    }

    /**
     * Get the executed query.
     *
     * @return String $query The executed query
     */
    public function query()
    {
        return $this->query;
    }

    /**
     * Returns the number of rows affected by the last query.
     *
     * @return mixed $number Number of rows in the result set.
     */
    public function affected_rows()
    {
        return $this->affected_rows;
    }

    /**
     * Returns the number of rows in the query. 
     *
     * @return Integer $number Number of rows in the result set. 
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
     * @return array $output Result set as array
     */
    public function result_array()
    {
        $output = [];

        if (!is_object($this->result))
        {
            return $output;
        }

        while ($row = $this->result->fetchArray(SQLITE3_ASSOC))
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
     * @param String $column Column or Alias name
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
     * @param String $column Column or Alias name
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