<?php

/**
 * SQLite3 database query builder class.
 *
 * SPDX-FileCopyrightText: Copyright 2013 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\SQLite3;

use Lunr\Gravity\SQLDMLQueryBuilder;

/**
 * This is a SQL query builder class for generating queries suitable for SQLite3.
 */
class SQLite3DMLQueryBuilder extends SQLDMLQueryBuilder
{

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        parent::__destruct();
    }

    /**
     * Construct and return a INSERT query.
     *
     * @return string The constructed query string.
     */
    public function get_insert_query(): string
    {
        if ($this->into == '')
        {
            return '';
        }

        $components   = [];
        $components[] = 'insertMode';
        $components[] = 'into';
        $components[] = 'columnNames';

        if ($this->selectStatement != '')
        {
            $components[] = 'selectStatement';
        }
        else
        {
            $components[] = 'values';
        }

        return 'INSERT ' . $this->implode_query($components);
    }

    /**
     * Construct and return a REPLACE query.
     *
     * @return string The constructed query string.
     */
    public function get_replace_query(): string
    {
        if ($this->into == '')
        {
            return '';
        }

        $components   = [];
        $components[] = 'into';
        $components[] = 'columnNames';

        if ($this->selectStatement != '')
        {
            $components[] = 'selectStatement';
        }
        else
        {
            $components[] = 'values';
        }

        return 'REPLACE ' . $this->implode_query($components);
    }

    /**
     * Not supported by SQLite.
     *
     * @param string $mode The delete mode you want to use
     *
     * @return $this Self reference
     */
    public function delete_mode($mode): static
    {
        return $this;
    }

    /**
     * Define the mode of the INSERT clause.
     *
     * @param string $mode The insert mode you want to use
     *
     * @return $this Self reference
     */
    public function insert_mode($mode): static
    {
        $mode = strtoupper($mode);

        switch ($mode)
        {
            case 'OR ROLLBACK':
            case 'OR ABORT':
            case 'OR REPLACE':
            case 'OR FAIL':
            case 'OR IGNORE':
                $this->insertMode['mode'] = $mode;
                break;
            default:
                break;
        }

        return $this;
    }

    /**
     * Not supported by sqlite.
     *
     * @param string $mode The replace mode you want to use
     *
     * @return $this Self reference
     */
    public function replace_mode($mode): static
    {
        return $this;
    }

    /**
     * Define the mode of the SELECT clause.
     *
     * @param string $mode The select mode you want to use
     *
     * @return $this Self reference
     */
    public function select_mode($mode): static
    {
        $mode = strtoupper($mode);

        switch ($mode)
        {
            case 'ALL':
            case 'DISTINCT':
                $this->selectMode['duplicates'] = $mode;
                break;
            default:
                break;
        }

        return $this;
    }

    /**
     * Define the mode of the UPDATE clause.
     *
     * @param string $mode The update mode you want to use
     *
     * @return $this Self reference
     */
    public function update_mode($mode): static
    {
        $mode = strtoupper($mode);

        switch ($mode)
        {
            case 'OR ROLLBACK':
            case 'OR ABORT':
            case 'OR REPLACE':
            case 'OR FAIL':
            case 'OR IGNORE':
                $this->updateMode['mode'] = $mode;
            default:
                break;
        }

        return $this;
    }

    /**
     * Define ON part of a JOIN clause with REGEXP comparator of the SQL statement.
     *
     * @param string $left   Left expression
     * @param string $right  Right expression
     * @param bool   $negate Whether to negate the comparison or not
     *
     * @return $this Self reference
     */
    public function on_regexp($left, $right, $negate = FALSE): static
    {
        $operator = ($negate === FALSE) ? 'REGEXP' : 'NOT REGEXP';
        $this->sql_condition($left, $right, $operator, 'ON');
        return $this;
    }

    /**
     * Define WHERE clause with the REGEXP condition of the SQL statement.
     *
     * @param string $left   Left expression
     * @param string $right  Right expression
     * @param bool   $negate Whether to negate the condition or not
     *
     * @return $this Self reference
     */
    public function where_regexp($left, $right, $negate = FALSE): static
    {
        $operator = ($negate === FALSE) ? 'REGEXP' : 'NOT REGEXP';
        $this->sql_condition($left, $right, $operator);
        return $this;
    }

    /**
     * Define GROUP BY clause of the SQL statement.
     *
     * @param string    $expr  Expression to group by
     * @param bool|null $order Not supported by SQLite
     *
     * @return $this Self reference
     */
    public function group_by(string $expr, ?bool $order = NULL): static
    {
        $this->sql_group_by($expr);
        return $this;
    }

    /**
     * Define HAVING clause with REGEXP comparator of the SQL statement.
     *
     * @param string $left   Left expression
     * @param string $right  Right expression
     * @param bool   $negate Whether to negate the comparison or not
     *
     * @return $this Self reference
     */
    public function having_regexp($left, $right, $negate = FALSE): static
    {
        $operator = ($negate === FALSE) ? 'REGEXP' : 'NOT REGEXP';
        $this->sql_condition($left, $right, $operator, 'HAVING');
        return $this;
    }

    /**
     * Not supported by SQLite.
     *
     * @param string $mode The lock mode you want to use
     *
     * @return $this Self reference
     */
    public function lock_mode($mode): static
    {
        return $this;
    }

    /**
     * Define which fields to return from a non SELECT query.
     *
     * @param string $fields Fields to return
     *
     * @return $this Self reference
     */
    public function returning(string $fields): static
    {
        $this->sql_select($fields, 'RETURNING');
        return $this;
    }

    /**
     * Define a EXCEPT, EXCEPT ALL or EXCEPT DISTINCT clause of the SQL statement.
     *
     * @param string $sqlQuery SQL query reference
     * @param string $operator EXCEPT operation to perform
     *
     * @return $this Self reference
     */
    public function except(string $sqlQuery, string $operator = ''): static
    {
        if (strtoupper($operator) !== 'DISTINCT')
        {
            $operator = NULL;
        }

        $this->sql_compound($sqlQuery, 'EXCEPT', $operator);
        return $this;
    }

    /**
     * Define a INTERSECT, INTERSECT ALL or INTERSECT DISTINCT clause of the SQL statement.
     *
     * @param string $sqlQuery SQL query reference
     * @param string $operator INTERSECT operation to perform
     *
     * @return $this Self reference
     */
    public function intersect(string $sqlQuery, string $operator = ''): static
    {
        if (strtoupper($operator) !== 'DISTINCT')
        {
            $operator = NULL;
        }

        $this->sql_compound($sqlQuery, 'INTERSECT', $operator);
        return $this;
    }

}

?>
