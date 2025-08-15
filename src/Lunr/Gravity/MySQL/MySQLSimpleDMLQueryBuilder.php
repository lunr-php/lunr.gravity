<?php

/**
 * MySQL/MariaDB database query builder class.
 *
 * SPDX-FileCopyrightText: Copyright 2013 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MySQL;

use Lunr\Gravity\DMLQueryBuilderInterface;

/**
 * This is a SQL query builder class for generating queries
 * suitable for either MySQL or MariaDB, performing automatic escaping
 * of input values where appropriate.
 */
class MySQLSimpleDMLQueryBuilder implements DMLQueryBuilderInterface
{

    /**
     * Instance of the MySQLDMLQueryBuilder class
     * @var MySQLDMLQueryBuilder
     */
    private $builder;

    /**
     * Instance of the MySQLQueryEscaper class.
     * @var MySQLQueryEscaper
     */
    protected $escaper;

    /**
     * Constructor.
     *
     * @param MySQLDMLQueryBuilder $builder Instance of the MySQLDMLQueryBuilder class
     * @param MySQLQueryEscaper    $escaper Instance of the MySQLQueryEscaper class
     */
    public function __construct($builder, $escaper)
    {
        $this->builder = $builder;
        $this->escaper = $escaper;
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        unset($this->builder);
        unset($this->escaper);
    }

    /**
     * Construct and return a SELECT query.
     *
     * @return string The constructed query string.
     */
    public function get_select_query(): string
    {
        return $this->builder->get_select_query();
    }

    /**
     * Construct and return a INSERT query.
     *
     * @return string The constructed query string.
     */
    public function get_insert_query(): string
    {
        return $this->builder->get_insert_query();
    }

    /**
     * Construct and return an UPDATE query.
     *
     * @return string The constructed query string.
     */
    public function get_update_query(): string
    {
        return $this->builder->get_update_query();
    }

    /**
     * Construct and return a DELETE query.
     *
     * @return string The constructed query string.
     */
    public function get_delete_query(): string
    {
        return $this->builder->get_delete_query();
    }

    /**
     * Construct and return a REPLACE query.
     *
     * @return string The constructed query string.
     */
    public function get_replace_query(): string
    {
        return $this->builder->get_replace_query();
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
        $this->builder->select_mode($mode);
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
        $this->builder->insert_mode($mode);
        return $this;
    }

    /**
     * Define the mode of the DELETE clause.
     *
     * @param string $mode The delete mode you want to use
     *
     * @return $this Self reference
     */
    public function delete_mode($mode): static
    {
        $this->builder->delete_mode($mode);
        return $this;
    }

    /**
     * Define the mode of the REPLACE clause.
     *
     * @param string $mode The replace mode you want to use
     *
     * @return $this Self reference
     */
    public function replace_mode($mode): static
    {
        $this->builder->replace_mode($mode);
        return $this;
    }

    /**
     * Define the lock mode for a transaction.
     *
     * @param string $mode The lock mode you want to use
     *
     * @return $this Self reference
     */
    public function lock_mode($mode): static
    {
        $this->builder->lock_mode($mode);
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
        $this->builder->update_mode($mode);
        return $this;
    }

    /**
     * Define a DELETE clause.
     *
     * @param string $tableReferences The table references to delete from
     *
     * @return $this Self reference
     */
    public function delete($tableReferences): static
    {
        $tables = '';

        foreach (explode(',', $tableReferences) as $table)
        {
            $tables .= $this->escape_alias($table, table: TRUE) . ', ';
        }

        $this->builder->delete(rtrim($tables, ', '));
        return $this;
    }

    /**
     * Define INTO clause of the SQL statement.
     *
     * @param string $table Table reference
     *
     * @return $this Self reference
     */
    public function into($table): static
    {
        $table = $this->escaper->table($table);
        $this->builder->into($table);

        return $this;
    }

    /**
     * Define SET clause of the SQL statement.
     *
     * @param array $set Array containing escaped key->value pairs to be set
     *
     * @return $this Self reference
     */
    public function set($set): static
    {
        $this->builder->set($set);
        return $this;
    }

    /**
     * Define Column names of the affected by Insert or Update SQL statement.
     *
     * @param array $keys Array containing escaped field names to be set
     *
     * @return $this Self reference
     */
    public function column_names($keys): static
    {
        $keys = array_map([ $this->escaper, 'column' ], $keys);
        $this->builder->column_names($keys);

        return $this;
    }

    /**
     * Define Values for Insert or Update SQL statement.
     *
     * @param array $values Array containing escaped values to be set
     *
     * @return $this Self reference
     */
    public function values($values): static
    {
        $this->builder->values($values);
        return $this;
    }

    /**
     * Define a Select statement for Insert statement.
     *
     * @param string $select SQL Select statement to be used in Insert
     *
     * @return $this Self reference
     */
    public function select_statement($select): static
    {
        $this->builder->select_statement($select);
        return $this;
    }

    /**
     * Define a UPDATE clause.
     *
     * @param string $tableReferences The tables to update
     *
     * @return $this Self reference
     */
    public function update(string $tableReferences): static
    {
        $tables = '';

        foreach (explode(',', $tableReferences) as $table)
        {
            $tables .= $this->escape_alias($table, table: TRUE) . ', ';
        }

        $this->builder->update(rtrim($tables, ', '));
        return $this;
    }

    /**
     * Define a SELECT clause.
     *
     * @param string $select The column(s) to select
     *
     * @return $this Self reference
     */
    public function select($select): static
    {
        $columns = '';

        foreach (explode(',', $select) as $column)
        {
            $columns .= $this->escape_alias($column, table: FALSE) . ', ';
        }

        $this->builder->select(rtrim($columns, ', '));
        return $this;
    }

    /**
     * Define FROM clause of the SQL statement.
     *
     * @param string $tableReference Table reference
     * @param array  $indexHints     Array of Index Hints
     *
     * @return $this Self reference
     */
    public function from($tableReference, $indexHints = NULL): static
    {
        $this->builder->from($this->escape_alias($tableReference), $indexHints);
        return $this;
    }

    /**
     * Define JOIN clause of the SQL statement.
     *
     * @param string $tableReference Table reference
     * @param string $type           Type of JOIN operation to perform.
     * @param array  $indexHints     Array of Index Hints
     *
     * @return $this Self reference
     */
    public function join($tableReference, $type = 'INNER', $indexHints = NULL): static
    {
        $this->builder->join($this->escape_alias($tableReference), $type, $indexHints);
        return $this;
    }

    /**
     * Define USING part of the SQL statement.
     *
     * @param string $columnList Columns to use.
     *
     * @return $this Self reference
     */
    public function using($columnList): static
    {
        $columns = '';

        foreach (explode(',', $columnList) as $column)
        {
            $columns .= $this->escaper->column(trim($column)) . ', ';
        }

        $this->builder->using(rtrim($columns, ', '));
        return $this;
    }

    /**
     * Define ON part of a JOIN clause of the SQL statement.
     *
     * @param string $left     Left expression
     * @param string $right    Right expression
     * @param string $operator Comparison operator
     *
     * @return $this Self reference
     */
    public function on($left, $right, $operator = '='): static
    {
        $this->builder->on($this->escaper->column($left), $this->escaper->column($right), $operator);
        return $this;
    }

    /**
     * Define ON part of a JOIN clause with LIKE comparator of the SQL statement.
     *
     * @param string $left   Left expression
     * @param string $right  Right expression
     * @param bool   $negate Whether to negate the comparison or not
     *
     * @return $this Self reference
     */
    public function on_like($left, $right, $negate = FALSE): static
    {
        $this->builder->on_like($this->escaper->column($left), $right, $negate);
        return $this;
    }

    /**
     * Define ON part of a JOIN clause with IN comparator of the SQL statement.
     *
     * @param string $left   Left expression
     * @param string $right  Right expression
     * @param bool   $negate Whether to negate the comparison or not
     *
     * @return $this Self reference
     */
    public function on_in(string $left, string $right, bool $negate = FALSE): static
    {
        $this->builder->on_in($this->escaper->column($left), $this->escaper->query_value($right), $negate);
        return $this;
    }

    /**
     * Define ON part of a JOIN clause with IN comparator of the SQL statement.
     *
     * @param string $left   Left expression
     * @param array  $right  Right expression
     * @param bool   $negate Whether to negate the comparison or not
     *
     * @return $this Self reference
     */
    public function on_in_list(string $left, array $right, bool $negate = FALSE): static
    {
        $right = array_map([ $this->escaper, 'value' ], $right);

        $this->builder->on_in($this->escaper->column($left), $this->escaper->list_value($right), $negate);
        return $this;
    }

    /**
     * Define ON part of a JOIN clause with BETWEEN comparator of the SQL statement.
     *
     * @param string $left   Left expression
     * @param string $lower  The lower bound of the between condition
     * @param string $upper  The upper bound of the between condition
     * @param bool   $negate Whether to negate the comparison or not
     *
     * @return $this Self reference
     */
    public function on_between($left, $lower, $upper, $negate = FALSE): static
    {
        $left  = $this->escaper->column($left);
        $lower = $this->escaper->value($lower);
        $upper = $this->escaper->value($upper);

        $this->builder->on_between($left, $lower, $upper, $negate);
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
        $this->builder->on_regexp($this->escaper->column($left), $right, $negate);
        return $this;
    }

    /**
     * Define ON part of a JOIN clause with the NULL condition.
     *
     * @param string $left   Left expression
     * @param bool   $negate Whether to negate the condition or not
     *
     * @return $this Self reference
     */
    public function on_null($left, $negate = FALSE): static
    {
        $this->builder->on_null($this->escaper->column($left), $negate);
        return $this;
    }

    /**
     * Open ON group.
     *
     * @return $this Self reference
     */
    public function start_on_group(): static
    {
        $this->builder->start_on_group();
        return $this;
    }

    /**
     * Close ON group.
     *
     * @return $this Self reference
     */
    public function end_on_group(): static
    {
        $this->builder->end_on_group();
        return $this;
    }

    /**
     * Define WHERE clause of the SQL statement.
     *
     * @param string $left     Left expression
     * @param string $right    Right expression
     * @param string $operator Comparison operator
     *
     * @return $this Self reference
     */
    public function where($left, $right, $operator = '='): static
    {
        $this->builder->where($this->escaper->column($left), $this->escaper->value($right), $operator);
        return $this;
    }

    /**
     * Open WHERE group.
     *
     * @return $this Self reference
     */
    public function start_where_group(): static
    {
        $this->builder->start_where_group();
        return $this;
    }

    /**
     * Close WHERE group.
     *
     * @return $this Self reference
     */
    public function end_where_group(): static
    {
        $this->builder->end_where_group();
        return $this;
    }

    /**
     * Define WHERE clause with LIKE comparator of the SQL statement.
     *
     * @param string $left   Left expression
     * @param string $right  Right expression
     * @param bool   $negate Whether to negate the comparison or not
     *
     * @return $this Self reference
     */
    public function where_like($left, $right, $negate = FALSE): static
    {
        $this->builder->where_like($this->escaper->column($left), $right, $negate);
        return $this;
    }

    /**
     * Define WHERE clause with the IN condition of the SQL statement.
     *
     * @param string $left   Left expression
     * @param string $right  Right expression
     * @param bool   $negate Whether to negate the condition or not
     *
     * @return $this Self reference
     */
    public function where_in(string $left, string $right, bool $negate = FALSE): static
    {
        $this->builder->where_in($this->escaper->column($left), $this->escaper->query_value($right), $negate);
        return $this;
    }

    /**
     * Define WHERE clause with the IN condition of the SQL statement.
     *
     * @param string $left   Left expression
     * @param array  $right  Right expression
     * @param bool   $negate Whether to negate the condition or not
     *
     * @return $this Self reference
     */
    public function where_in_list(string $left, array $right, bool $negate = FALSE): static
    {
        $right = array_map([ $this->escaper, 'value' ], $right);

        $this->builder->where_in($this->escaper->column($left), $this->escaper->list_value($right), $negate);
        return $this;
    }

    /**
     * Define WHERE clause with the BETWEEN condition of the SQL statement.
     *
     * @param string $left   Left expression
     * @param string $lower  The lower bound of the between condition
     * @param string $upper  The upper bound of the between condition
     * @param bool   $negate Whether to negate the condition or not
     *
     * @return $this Self reference
     */
    public function where_between($left, $lower, $upper, $negate = FALSE): static
    {
        $left  = $this->escaper->column($left);
        $lower = $this->escaper->value($lower);
        $upper = $this->escaper->value($upper);

        $this->builder->where_between($left, $lower, $upper, $negate);
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
        $this->builder->where_regexp($this->escaper->column($left), $right, $negate);
        return $this;
    }

    /**
     * Define WHERE clause with the NULL condition.
     *
     * @param string $left   Left expression
     * @param bool   $negate Whether to negate the condition or not
     *
     * @return $this Self reference
     */
    public function where_null($left, $negate = FALSE): static
    {
        $this->builder->where_null($this->escaper->column($left), $negate);
        return $this;
    }

    /**
     * Define GROUP BY clause of the SQL statement.
     *
     * @param string $expr  Expression to group by
     * @param bool   $order Order ASCending/TRUE or DESCending/FALSE, default no order/NULL
     *
     * @return $this Self reference
     */
    public function group_by($expr, $order = NULL): static
    {
        $this->builder->group_by($this->escaper->column($expr), $order);
        return $this;
    }

    /**
     * Define HAVING clause of the SQL statement.
     *
     * @param string $left     Left expression
     * @param string $right    Right expression
     * @param string $operator Comparison operator
     *
     * @return $this Self reference
     */
    public function having($left, $right, $operator = '='): static
    {
        $this->builder->having($this->escaper->column($left), $this->escaper->value($right), $operator);
        return $this;
    }

    /**
     * Set logical connector 'AND'.
     *
     * @deprecated Use `and()` instead
     *
     * @return $this Self reference
     */
    public function sql_and(): static
    {
        $this->builder->sql_and();
        return $this;
    }

    /**
     * Set logical connector 'AND'.
     *
     * @return $this Self reference
     */
    public function and(): static
    {
        $this->builder->and();
        return $this;
    }

    /**
     * Set logical connector 'OR'.
     *
     * @deprecated Use `or()` instead
     *
     * @return $this Self reference
     */
    public function sql_or(): static
    {
        $this->builder->sql_or();
        return $this;
    }

    /**
     * Set logical connector 'OR'.
     *
     * @return $this Self reference
     */
    public function or(): static
    {
        $this->builder->or();
        return $this;
    }

    /**
     * Set logical connector 'XOR'.
     *
     * @return $this Self reference
     */
    public function xor(): static
    {
        $this->builder->xor();
        return $this;
    }

    /**
     * Define HAVING clause with LIKE comparator of the SQL statement.
     *
     * @param string $left   Left expression
     * @param string $right  Right expression
     * @param bool   $negate Whether to negate the comparison or not
     *
     * @return $this Self reference
     */
    public function having_like($left, $right, $negate = FALSE): static
    {
        $this->builder->having_like($this->escaper->column($left), $right, $negate);
        return $this;
    }

    /**
     * Define HAVING clause with IN comparator of the SQL statement.
     *
     * @param string $left   Left expression
     * @param string $right  Right expression
     * @param bool   $negate Whether to negate the comparison or not
     *
     * @return $this Self reference
     */
    public function having_in(string $left, string $right, bool $negate = FALSE): static
    {
        $this->builder->having_in($this->escaper->column($left), $this->escaper->query_value($right), $negate);
        return $this;
    }

    /**
     * Define HAVING clause with IN comparator of the SQL statement.
     *
     * @param string $left   Left expression
     * @param array  $right  Right expression
     * @param bool   $negate Whether to negate the comparison or not
     *
     * @return $this Self reference
     */
    public function having_in_list(string $left, array $right, bool $negate = FALSE): static
    {
        $right = array_map([ $this->escaper, 'value' ], $right);

        $this->builder->having_in($this->escaper->column($left), $this->escaper->list_value($right), $negate);
        return $this;
    }

    /**
     * Define HAVING clause with BETWEEN comparator of the SQL statement.
     *
     * @param string $left   Left expression
     * @param string $lower  The lower bound of the between condition
     * @param string $upper  The upper bound of the between condition
     * @param bool   $negate Whether to negate the comparison or not
     *
     * @return $this Self reference
     */
    public function having_between($left, $lower, $upper, $negate = FALSE): static
    {
        $left  = $this->escaper->column($left);
        $lower = $this->escaper->value($lower);
        $upper = $this->escaper->value($upper);

        $this->builder->having_between($left, $lower, $upper, $negate);
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
        $this->builder->having_regexp($this->escaper->column($left), $right, $negate);
        return $this;
    }

    /**
     * Define HAVING clause with the NULL condition.
     *
     * @param string $left   Left expression
     * @param bool   $negate Whether to negate the condition or not
     *
     * @return $this Self reference
     */
    public function having_null($left, $negate = FALSE): static
    {
        $this->builder->having_null($this->escaper->column($left), $negate);
        return $this;
    }

    /**
     * Open having group.
     *
     * @return $this Self reference
     */
    public function start_having_group(): static
    {
        $this->builder->start_having_group();
        return $this;
    }

    /**
     * Close having group.
     *
     * @return $this Self reference
     */
    public function end_having_group(): static
    {
        $this->builder->end_having_group();
        return $this;
    }

    /**
     * Define ORDER BY clause in the SQL statement.
     *
     * @param string $expr Expression to order by
     * @param bool   $asc  Order ASCending/TRUE or DESCending/FALSE
     *
     * @return $this Self reference
     */
    public function order_by($expr, $asc = TRUE): static
    {
        $this->builder->order_by($this->escaper->column($expr), $asc);
        return $this;
    }

    /**
     * Define a LIMIT clause of the SQL statement.
     *
     * @param int $amount The amount of elements to retrieve
     * @param int $offset Start retrieving elements from a specific index
     *
     * @return $this Self reference
     */
    public function limit($amount, $offset = -1): static
    {
        $this->builder->limit($this->escaper->intvalue($amount), $this->escaper->intvalue($offset));
        return $this;
    }

    /**
     * Define a UNION or UNION ALL clause of the SQL statement.
     *
     * @param string $sqlQuery SQL query reference
     * @param string $type     Type of UNION operation to perform.
     *
     * @return $this Self reference
     */
    public function union(string $sqlQuery, string $type = ''): static
    {
        $this->builder->union($this->escaper->query_value($sqlQuery), $type);
        return $this;
    }

    /**
     * Define a with clause.
     *
     * @param string $alias       The alias of the WITH statement
     * @param string $sqlQuery    Sql query reference
     * @param array  $columnNames An optional parameter to give the result columns a name
     *
     * @return $this Self reference
     */
    public function with($alias, $sqlQuery, $columnNames = NULL): static
    {
        $this->builder->with($alias, $sqlQuery, $columnNames);
        return $this;
    }

    /**
     * Define a recursive WITH clause.
     *
     * @param string $alias          The alias of the WITH statement
     * @param string $anchorQuery    The initial select statement
     * @param string $recursiveQuery The select statement that selects recursively out of the initial query
     * @param bool   $unionAll       True for UNION ALL false for UNION
     * @param array  $columnNames    An optional parameter to give the result columns a name
     *
     * @return $this Self reference
     */
    public function with_recursive($alias, $anchorQuery, $recursiveQuery, $unionAll = FALSE, $columnNames = NULL): static
    {
        $this->builder->with_recursive($alias, $anchorQuery, $recursiveQuery, $unionAll, $columnNames);
        return $this;
    }

    /**
     * Set ON DUPLICATE KEY UPDATE clause.
     *
     * @param string $set Action to perform on conflict
     *
     * @return $this Self reference
     */
    public function on_duplicate_key_update($set): static
    {
        $this->builder->on_duplicate_key_update($set);
        return $this;
    }

    /**
     * Escape a table reference.
     *
     * @param string $locationReference A location reference
     * @param bool   $table             Whether to escape a table or a result_column
     *
     * @return string Escaped location reference
     */
    protected function escape_alias(string $locationReference, bool $table = TRUE): string
    {
        $method = $table ? 'table' : 'result_column';

        if (strpos($locationReference, ' AS '))
        {
            $parts = explode(' AS ', $locationReference);
            return $this->escaper->{$method}($parts[0], $parts[1]);
        }

        if (strpos($locationReference, ' as '))
        {
            $parts = explode(' as ', $locationReference);
            return $this->escaper->{$method}($parts[0], $parts[1]);
        }

        return $this->escaper->{$method}($locationReference);
    }

}

?>
