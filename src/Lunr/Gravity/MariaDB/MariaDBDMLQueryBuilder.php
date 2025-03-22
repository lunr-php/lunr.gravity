<?php

/**
 * MySQL/MariaDB database query builder class.
 *
 * SPDX-FileCopyrightText: Copyright 2018 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MariaDB;

use Lunr\Gravity\MySQL\MySQLDMLQueryBuilder;

/**
 * This is a SQL query builder class for generating queries for MariaDB.
 */
class MariaDBDMLQueryBuilder extends MySQLDMLQueryBuilder
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
     * Define which fields to return from a non SELECT query.
     *
     * @param string $fields Fields to return
     *
     * @return $this Self reference
     */
    public function returning($fields): static
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
        $this->sql_compound($sqlQuery, 'EXCEPT', strtoupper($operator));
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
        $this->sql_compound($sqlQuery, 'INTERSECT', strtoupper($operator));
        return $this;
    }

}

?>
