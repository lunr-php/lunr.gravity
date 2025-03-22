<?php

/**
 * MySQL/MariaDB database query builder class.
 *
 * SPDX-FileCopyrightText: Copyright 2018 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MariaDB;

use Lunr\Gravity\MySQL\MySQLQueryEscaper;
use Lunr\Gravity\MySQL\MySQLSimpleDMLQueryBuilder;

/**
 * This is a SQL query builder class for generating queries
 * only suitable for MariaDB, performing automatic escaping
 * of input values where appropriate.
 */
class MariaDBSimpleDMLQueryBuilder extends MySQLSimpleDMLQueryBuilder
{

    /**
     * Instance of the MariaDBDMLQueryBuilder class
     * @var MariaDBDMLQueryBuilder
     */
    private $builder;

    /**
     * Constructor.
     *
     * @param MariaDBDMLQueryBuilder $builder Instance of the MySQLDMLQueryBuilder class
     * @param MySQLQueryEscaper      $escaper Instance of the MySQLQueryEscaper class
     */
    public function __construct($builder, $escaper)
    {
        parent::__construct($builder, $escaper);

        $this->builder = $builder;
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        unset($this->builder);

        parent::__destruct();
    }

    /**
     * Define which columns to return from a non SELECT query.
     *
     * @param string $returning Columns to return
     *
     * @return $this Self reference
     */
    public function returning($returning): static
    {
        $columns = '';
        foreach (explode(',', $returning) as $column)
        {
            if ($columns !== '')
            {
                $columns .= ', ';
            }

            $columns .= $this->escape_alias($column, FALSE);
        }

        $this->builder->returning($columns);
        return $this;
    }

    /**
     * Define a INTERSECT, INTERSECT DISTINCT or INTERSECT ALL clause of the SQL statement.
     *
     * @param string $sqlQuery SQL query reference
     * @param string $type     Type of INTERSECT operation to perform.
     *
     * @return $this Self reference
     */
    public function intersect(string $sqlQuery, string $type = ''): static
    {
        $this->builder->intersect($this->escaper->query_value($sqlQuery), $type);
        return $this;
    }

    /**
     * Define a EXCEPT, EXCEPT DISTINCT or EXCEPT ALL clause of the SQL statement.
     *
     * @param string $sqlQuery SQL query reference
     * @param string $type     Type of EXCEPT operation to perform.
     *
     * @return $this Self reference
     */
    public function except($sqlQuery, $type = NULL): static
    {
        $this->builder->except($this->escaper->query_value($sqlQuery), $type);
        return $this;
    }

}

?>
