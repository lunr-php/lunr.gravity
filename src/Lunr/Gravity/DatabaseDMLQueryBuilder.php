<?php

/**
 * Abstract database query builder class.
 *
 * SPDX-FileCopyrightText: Copyright 2012 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity;

use Lunr\Gravity\Exceptions\MissingTableReferenceException;

/**
 * This class defines abstract database query building.
 */
abstract class DatabaseDMLQueryBuilder implements DMLQueryBuilderInterface
{

    /**
     * SQL Query part: SELECT clause
     * @var string
     */
    protected $select;

    /**
     * SQL Query part: SELECT mode
     * @var array
     */
    protected $selectMode;

    /**
     * SQL Query part: lock mode
     * @var string
     */
    protected $lockMode;

    /**
     * SQL Query part: DELETE clause
     * @var string
     */
    protected $delete;

    /**
     * SQL Query part: DELETE mode
     * @var array
     */
    protected $deleteMode;

    /**
     * SQL Query part: FROM clause
     * @var string
     */
    protected $from;

    /**
     * SQL Query part: INTO clause
     * @var string
     */
    protected $into;

    /**
     * SQL Query part: INSERT modes
     * @var array
     */
    protected $insertMode;

    /**
     * SQL Query part: UPDATE clause
     * @var string
     */
    protected $update;

    /**
     * SQL Query part: UPDATE modes
     * @var array
     */
    protected $updateMode;

    /**
     * SQL Query part: SET clause
     * @var string
     */
    protected $set;

    /**
     * SQL Query part: Column names
     * @var string
     */
    protected $columnNames;

    /**
     * SQL Query part: VALUES
     * @var string
     */
    protected $values;

    /**
     * SQL Query part: UPSERT clause
     * @var string
     */
    protected $upsert;

    /**
     * SQL Query part: SELECT statement
     * @var string
     */
    protected $selectStatement;

    /**
     * SQL Query part: JOIN clause
     * @var string
     */
    protected $join;

    /**
     * SQL Query part: WHERE clause
     * @var string
     */
    protected $where;

    /**
     * SQL Query part: GROUP BY clause
     * @var string
     */
    protected $groupBy;

    /**
     * SQL Query part: HAVING clause
     * @var string
     */
    protected $having;

    /**
     * SQL Query part: ORDER BY clause
     * @var string
     */
    protected $orderBy;

    /**
     * SQL Query part: LIMIT clause
     * @var string
     */
    protected $limit;

    /**
     * SQL Query part: WHERE clause
     * @var string
     */
    protected $compound;

    /**
     * SQL Query part: Logical connector of expressions
     * @var string
     */
    protected $connector;

    /**
     * SQL Query part: Boolean identifying if the join is not finished
     * @var bool
     */
    protected $isUnfinishedJoin;

    /**
     * SQL Query part: string identifying if the join type is type "using" or "on"
     * @var string
     */
    protected $joinType;

    /**
     * SQL Query part: String that contains the with query
     * @var string
     */
    protected $with;

    /**
     * Whether a recursive with statement is used or not
     * @var bool
     */
    protected $isRecursive;

    /**
     * SQL Query part: returning clause
     * @var string
     */
    protected $returning;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->select           = '';
        $this->selectMode       = [];
        $this->update           = '';
        $this->updateMode       = [];
        $this->delete           = '';
        $this->deleteMode       = [];
        $this->from             = '';
        $this->join             = '';
        $this->where            = '';
        $this->groupBy          = '';
        $this->having           = '';
        $this->orderBy          = '';
        $this->limit            = '';
        $this->connector        = '';
        $this->into             = '';
        $this->insertMode       = [];
        $this->set              = '';
        $this->columnNames      = '';
        $this->values           = '';
        $this->upsert           = '';
        $this->selectStatement  = '';
        $this->compound         = '';
        $this->isUnfinishedJoin = FALSE;
        $this->joinType         = '';
        $this->with             = '';
        $this->isRecursive      = FALSE;
        $this->returning        = '';
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        unset($this->select);
        unset($this->selectMode);
        unset($this->update);
        unset($this->updateMode);
        unset($this->delete);
        unset($this->deleteMode);
        unset($this->from);
        unset($this->join);
        unset($this->where);
        unset($this->groupBy);
        unset($this->having);
        unset($this->orderBy);
        unset($this->limit);
        unset($this->compound);
        unset($this->connector);
        unset($this->into);
        unset($this->insertMode);
        unset($this->set);
        unset($this->columnNames);
        unset($this->values);
        unset($this->upsert);
        unset($this->selectStatement);
        unset($this->isUnfinishedJoin);
        unset($this->joinType);
        unset($this->with);
        unset($this->isRecursive);
        unset($this->returning);
    }

    /**
     * Construct and return a SELECT query.
     *
     * @return string $query The constructed query string.
     */
    public function get_select_query(): string
    {
        $components = [];

        array_push($components, 'selectMode', 'select', 'from', 'join', 'where');
        array_push($components, 'groupBy', 'having', 'orderBy', 'limit', 'lockMode');

        $withQuery = '';

        if ($this->with != '')
        {
            if ($this->isRecursive == TRUE)
            {
                $withQuery = 'WITH RECURSIVE ' . $this->with . ' ';
            }
            else
            {
                $withQuery = 'WITH ' . $this->with . ' ';
            }
        }

        $standard = $withQuery . 'SELECT ' . $this->implode_query($components);
        if ($this->compound == '')
        {
            return $standard;
        }

        $components   = [];
        $components[] = 'compound';

        return '(' . $standard . ') ' . $this->implode_query($components);
    }

    /**
     * Construct and return a DELETE query.
     *
     * @return string $query The constructed query string.
     */
    public function get_delete_query(): string
    {
        if ($this->from == '')
        {
            throw new MissingTableReferenceException('No from() in delete query!');
        }

        $components = [];
        array_push($components, 'deleteMode', 'delete', 'from', 'join', 'where');

        if (($this->delete == '') && ($this->join == ''))
        {
            array_push($components, 'orderBy', 'limit', 'returning');
        }

        return 'DELETE ' . $this->implode_query($components);
    }

    /**
     * Construct and return a INSERT query.
     *
     * @return string $query The constructed query string.
     */
    public function get_insert_query(): string
    {
        if ($this->into == '')
        {
            throw new MissingTableReferenceException('No into() in insert query!');
        }

        $components   = [];
        $components[] = 'insertMode';
        $components[] = 'into';

        if ($this->selectStatement != '')
        {
            $components[] = 'columnNames';
            $components[] = 'selectStatement';

            $valid = [ 'HIGH_PRIORITY', 'LOW_PRIORITY', 'IGNORE' ];

            $this->insertMode = array_intersect($this->insertMode, $valid);
        }
        elseif ($this->set != '')
        {
            $components[] = 'set';
        }
        else
        {
            $components[] = 'columnNames';
            $components[] = 'values';
        }

        $components[] = 'upsert';

        if ($this->returning != '')
        {
            $components[] = 'returning';
        }

        return 'INSERT ' . $this->implode_query($components);
    }

    /**
     * Construct and return a REPLACE query.
     *
     * @return string $query The constructed query string.
     */
    public function get_replace_query(): string
    {
        if ($this->into == '')
        {
            throw new MissingTableReferenceException('No into() in replace query!');
        }

        $valid = [ 'LOW_PRIORITY', 'DELAYED' ];

        $this->insertMode = array_intersect($this->insertMode, $valid);

        $components   = [];
        $components[] = 'insertMode';
        $components[] = 'into';

        if ($this->selectStatement != '')
        {
            $components[] = 'columnNames';
            $components[] = 'selectStatement';
        }
        elseif ($this->set != '')
        {
            $components[] = 'set';
        }
        else
        {
            $components[] = 'columnNames';
            $components[] = 'values';
        }

        if ($this->returning != '')
        {
            $components[] = 'returning';
        }

        return 'REPLACE ' . $this->implode_query($components);
    }

    /**
     * Construct and return an UPDATE query.
     *
     * @return string $query The constructed query string.
     */
    public function get_update_query(): string
    {
        if ($this->update == '')
        {
            throw new MissingTableReferenceException('No update() in update query!');
        }

        $valid = [ 'LOW_PRIORITY', 'IGNORE' ];

        $this->updateMode = array_intersect($this->updateMode, $valid);

        $components = [];
        array_push($components, 'updateMode', 'update', 'join', 'set', 'where');

        if ((strpos($this->update, ',') === FALSE) && $this->join == '')
        {
            $components[] = 'orderBy';
            $components[] = 'limit';
        }

        return 'UPDATE ' . $this->implode_query($components);
    }

    /**
     * Define a SELECT clause.
     *
     * @param string|null $select The columns to select
     * @param string      $base   Whether to construct SELECT or RETURNING
     *
     * @return void
     */
    protected function sql_select($select, $base = 'SELECT'): void
    {
        $part = ($base == 'RETURNING') ? 'returning' : 'select';

        if ($this->$part == '' && $base == 'RETURNING')
        {
            $this->$part = 'RETURNING ';
        }
        elseif ($this->$part != '')
        {
            $this->$part .= ', ';
        }

        $this->$part .= $select ?? 'NULL';
    }

    /**
     * Define a WITH clause.
     *
     * @param string      $alias          The alias of the WITH statement
     * @param string      $sqlQuery       Sql query reference
     * @param string|null $recursiveQuery The select statement that selects recursively out of the initial query
     * @param string|null $union          The union part of a recursive query
     * @param array|null  $columnNames    An optional parameter to give the result columns a name
     *
     * @return void
     */
    protected function sql_with($alias, $sqlQuery, $recursiveQuery = NULL, $union = NULL, $columnNames = NULL): void
    {
        if ($columnNames !== NULL)
        {
            $columnNames = ' (' . implode(', ', $columnNames) . ')';
        }

        if ($recursiveQuery != '')
        {
            $this->isRecursive = TRUE;

            if (!is_null($union))
            {
                $recursiveQuery = ' ' . $union . ' ' . $recursiveQuery;
            }
        }

        if ($this->with != '')
        {
            if ($recursiveQuery != '')
            {
                $this->with = $alias . $columnNames . ' AS ( ' . $sqlQuery . $recursiveQuery . ' ), ' . $this->with;
            }
            else
            {
                $this->with .= ', ' . $alias . $columnNames . ' AS ( ' . $sqlQuery . ' )';
            }
        }
        else
        {
            $this->with = $alias . $columnNames . ' AS ( ' . $sqlQuery . $recursiveQuery . ' )';
        }
    }

    /**
     * Define a UPDATE clause.
     *
     * @param string $tableReferences The tables to update
     *
     * @return void
     */
    protected function sql_update($tableReferences): void
    {
        if ($this->update != '')
        {
            $this->update .= ', ';
        }

        $this->update .= $tableReferences;
    }

    /**
     * Define a DELETE clause.
     *
     * @param string $delete The tables to delete from
     *
     * @return void
     */
    protected function sql_delete($delete): void
    {
        if ($this->delete != '')
        {
            $this->delete .= ', ';
        }

        $this->delete .= $delete;
    }

    /**
     * Define FROM clause of the SQL statement.
     *
     * @param string     $table      Table reference
     * @param array|null $indexHints Array of Index Hints
     *
     * @return void
     */
    protected function sql_from($table, $indexHints = NULL): void
    {
        if ($this->from == '')
        {
            $this->from = 'FROM ';
        }
        else
        {
            $this->from .= ', ';
        }

        $this->from .= $table . $this->prepare_index_hints($indexHints);
    }

    /**
     * Define JOIN clause of the SQL statement.
     *
     * @param string     $tableReference Table reference
     * @param string     $type           Type of JOIN operation to perform.
     * @param array|null $indexHints     Array of Index Hints
     *
     * @return void
     */
    protected function sql_join($tableReference, $type, $indexHints = NULL): void
    {
        $type = strtoupper($type);

        $join = ($type == 'STRAIGHT') ? 'STRAIGHT_JOIN ' : ltrim($type . ' JOIN ');

        if ($this->join != '')
        {
            $this->join .= ' ';
        }

        $this->join .= $join . $tableReference . $this->prepare_index_hints($indexHints);

        if (!(substr($type, 0, 7) == 'NATURAL'))
        {
            $this->isUnfinishedJoin = TRUE;
        }

        $this->joinType = '';
    }

    /**
     * Define USING clause of the SQL statement.
     *
     * @param string $columnList Column name to use.
     *
     * @return void
     */
    protected function sql_using($columnList): void
    {
        // Select join type.
        if ($this->joinType === '')
        {
            $this->joinType = 'using';
        }

        // Prevent USING and ON to be used at the same time.
        if ($this->joinType !== 'using')
        {
            return;
        }

        if ($this->isUnfinishedJoin)
        {
            $this->join            .= ' USING (';
            $this->isUnfinishedJoin = FALSE;
        }
        elseif (substr($this->join, -1) !== '(')
        {
            $this->join = rtrim($this->join, ')') . ', ';
        }

        $this->join .= $columnList . ')';
    }

    /**
     * Define INTO clause of the SQL statement.
     *
     * @param string $table Table reference
     *
     * @return void
     */
    protected function sql_into($table): void
    {
        $this->into = 'INTO ' . $table;
    }

    /**
     * Define SET clause of the SQL statement.
     *
     * @param array $set Array containing escaped key->value pairs to be set
     *
     * @return void
     */
    protected function sql_set($set): void
    {
        if ($this->set == '')
        {
            $this->set = 'SET ';
        }
        else
        {
            $this->set .= ', ';
        }

        foreach ($set as $key => $value)
        {
            $value = (is_null($value)) ? 'NULL' : $value;

            $this->set .= $key . ' = ' . $value . ', ';
        }

        $this->set = trim($this->set, ', ');
    }

    /**
     * Define Column names of the affected by Insert or Update SQL statement.
     *
     * @param array $keys Array containing escaped field names to be set
     *
     * @return void
     */
    protected function sql_column_names($keys): void
    {
        $this->columnNames = '(' . implode(', ', $keys) . ')';
    }

    /**
     * Define Values for Insert or Update SQL statement.
     *
     * @param array $values Array containing escaped values to be set, can be either an
     *                      array or an array of arrays
     *
     * @return void
     */
    protected function sql_values($values): void
    {
        if (empty($values))
        {
            return;
        }

        if ($this->values == '')
        {
            $this->values = 'VALUES ';
        }
        else
        {
            $this->values .= ', ';
        }

        if (!isset($values[0]) || !is_array($values[0]))
        {
            $values = [ $values ];
        }

        foreach ($values as $value)
        {
            $value = array_map(function ($entry) { return is_null($entry) ? 'NULL' : $entry; }, $value);

            $this->values .= '(' . implode(', ', $value) . '), ';
        }

        $this->values = trim($this->values, ', ');
    }

    /**
     * Define an Upsert clause for an Insert statement.
     *
     * @param string      $key    Upsert keyword
     * @param string      $action Action to perform on conflict
     * @param string|null $target Target to watch for conflicts
     *
     * @return void
     */
    protected function sql_upsert($key, $action, $target = NULL): void
    {
        $this->upsert = $key . ' ';

        if ($target !== NULL)
        {
            $this->upsert .= $target . ' ';
        }

        $this->upsert .= $action;
    }

    /**
     * Define a Select statement for Insert statement.
     *
     * @param string $select SQL Select statement to be used in Insert
     *
     * @return void
     */
    protected function sql_select_statement($select): void
    {
        if (strpos($select, 'SELECT') === 0)
        {
            $this->selectStatement = $select;
        }
    }

    /**
     * Define a conditional clause for the SQL statement.
     *
     * @param string $left     Left expression
     * @param string $right    Right expression
     * @param string $operator Comparison operator
     * @param string $base     Whether to construct WHERE, HAVING or ON
     *
     * @return void
     */
    protected function sql_condition($left, $right, $operator = '=', $base = 'WHERE'): void
    {
        $condition = ($base === 'ON') ? 'join' : strtolower($base);

        // select join type.
        if ($this->joinType === '' && $this->isUnfinishedJoin === TRUE && $base === 'ON')
        {
            $this->joinType = strtolower($base);
        }

        // Prevent USING and ON to be used at the same time.
        if ($this->joinType === 'using' && $condition === 'join')
        {
            return;
        }

        if (rtrim($this->$condition, '(') == '' || $this->isUnfinishedJoin)
        {
            if ($this->isUnfinishedJoin)
            {
                $this->$condition .= ' ' . $base . ' ';
            }
            else
            {
                $this->$condition = $base . ' ' . $this->$condition;
            }

            $this->connector        = '';
            $this->isUnfinishedJoin = FALSE;
        }
        elseif ($this->connector != '')
        {
            $this->$condition .= ' ' . $this->connector . ' ';
            $this->connector   = '';
        }
        elseif (substr($this->$condition, -1) !== '(')
        {
            $this->$condition .= ' AND ';
        }

        $this->$condition .= "$left $operator $right";
    }

    /**
     * Define a compound clause for the SQL statement.
     *
     * @param string $sqlQuery Left expression
     * @param string $type     Whether to construct UNION, EXCEPT or INTERSECT
     * @param mixed  $operator Whether to add ALL, DISTINCT or default
     *
     * @return void
     */
    protected function sql_compound($sqlQuery, $type, $operator = NULL): void
    {
        if ($this->compound != '')
        {
            $this->compound .= ' ';
        }

        if ($operator === 'ALL' || $operator === 'DISTINCT')
        {
            $base = $type . ' ' . $operator;
        }
        else
        {
            $base = $type;
        }

        $this->compound .= $base . ' ' . $sqlQuery;
    }

    /**
     * Define a ORDER BY clause of the SQL statement.
     *
     * @param string $expr Expression to order by
     * @param bool   $asc  Order ASCending/TRUE or DESCending/FALSE
     *
     * @return void
     */
    protected function sql_order_by($expr, $asc = TRUE): void
    {
        $direction = ($asc === TRUE) ? 'ASC' : 'DESC';

        if ($this->orderBy == '')
        {
            $this->orderBy = 'ORDER BY ';
        }
        else
        {
            $this->orderBy .= ', ';
        }

        $this->orderBy .= $expr . ' ' . $direction;
    }

    /**
     * Define a LIMIT clause for the SQL statement.
     *
     * @param int $amount The amount of elements to retrieve
     * @param int $offset Start retrieving elements from a specific index
     *
     * @return void
     */
    protected function sql_limit($amount, $offset = -1): void
    {
        $this->limit = "LIMIT $amount";

        if ($offset > -1)
        {
            $this->limit .= " OFFSET $offset";
        }
    }

    /**
     * Set a logical connector.
     *
     * @param string $connector Logical connector to set
     *
     * @return void
     */
    protected function sql_connector($connector): void
    {
        $this->connector = $connector;
    }

    /**
     * Define a GROUP BY clause of the SQL statement.
     *
     * @param string $expr Expression to group by
     *
     * @return void
     */
    protected function sql_group_by($expr): void
    {
        if ($this->groupBy == '')
        {
            $this->groupBy = 'GROUP BY ';
        }
        else
        {
            $this->groupBy .= ', ';
        }

        $this->groupBy .= $expr;
    }

    /**
     * Construct SQL query string.
     *
     * @param array $components Array of SQL query components to use to construct the query.
     *
     * @return string $sql The constructed SQL query
     */
    protected function implode_query($components): string
    {
        $sql = '';

        foreach ($components as $component)
        {
            if (isset($this->$component) && ($this->$component != ''))
            {
                if (($component === 'selectMode') || ($component === 'deleteMode')
                    || ($component === 'insertMode') || ($component === 'updateMode')
                )
                {
                    $sql .= implode(' ', array_unique($this->$component)) . ' ';
                }
                else
                {
                    $sql .= $this->$component . ' ';
                }
            }
            elseif ($component === 'select')
            {
                $sql .= '* ';
            }
        }

        $sql = trim($sql);

        return ($sql == '*') ? '' : $sql;
    }

    /**
     * Prepare the list of index hints for a table reference.
     *
     * @param array|null $indexHints Array of Index Hints
     *
     * @return string $hints Comma separated list of index hints.
     */
    protected function prepare_index_hints(?array $indexHints): string
    {
        if (!empty($indexHints))
        {
            $indexHints = array_diff($indexHints, [ NULL ]);
            $hints      = ' ' . implode(', ', $indexHints);
        }
        else
        {
            $hints = '';
        }

        return $hints;
    }

    /**
     * Open the parentheses for the sql condition.
     *
     * @param string $base String indication Statement to group
     *
     * @return void
     */
    protected function sql_group_start($base = 'WHERE'): void
    {
        $condition = ($base === 'ON') ? 'join' : strtolower($base);

        // select join type.
        if ($this->joinType === '' && $this->isUnfinishedJoin === TRUE && $base === 'ON')
        {
            $this->joinType = strtolower($base);
        }

        // Prevent USING and ON to be used at the same time.
        if ($this->joinType === 'using' && $condition === 'join')
        {
            return;
        }

        if ($this->isUnfinishedJoin)
        {
            $this->$condition      .= 'ON ';
            $this->isUnfinishedJoin = FALSE;
        }
        elseif ($this->connector != '')
        {
            if (!empty($this->$condition))
            {
                $this->$condition .= ' ' . $this->connector . ' ';
            }

            $this->connector = '';
        }
        elseif (!empty($this->$condition) && substr($this->$condition, -1) !== '(')
        {
            $this->$condition .= ' AND ';
        }

        $this->$condition .= '(';
    }

    /**
     * Close the parentheses for the sql condition.
     *
     * @param string $condition String indication Statement to group
     *
     * @return void
     */
    protected function sql_group_end($condition = 'WHERE'): void
    {
        $condition = ($condition === 'ON') ? 'join' : strtolower($condition);

        // Prevent USING and ON to be used at the same time.
        if ($this->joinType === 'using' && $condition === 'join')
        {
            return;
        }

        $this->$condition .= ')';
    }

}

?>
