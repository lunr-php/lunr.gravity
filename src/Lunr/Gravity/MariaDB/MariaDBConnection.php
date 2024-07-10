<?php

/**
* MariaDB database connection class.
*
 * SPDX-FileCopyrightText: Copyright 2018 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
*/

namespace Lunr\Gravity\MariaDB;

use Lunr\Gravity\MySQL\MySQLConnection;
use Lunr\Core\Configuration;
use Psr\Log\LoggerInterface;
use mysqli;

/**
* MariaDB database access class.
*/
class MariaDBConnection extends MySQLConnection
{

    /**
     * Constructor.
     *
     * @param Configuration   $configuration Shared instance of the configuration class
     * @param LoggerInterface $logger        Shared instance of a logger class
     * @param MySQLi          $mysqli        Instance of the mysqli class
     */
    public function __construct(Configuration $configuration, LoggerInterface $logger, MySQLi $mysqli)
    {
        parent::__construct($configuration, $logger, $mysqli);
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        parent::__destruct();
    }

    /**
     * Return a new instance of a QueryBuilder object.
     *
     * @param bool $simple Whether to return a simple query builder or an advanced one.
     *
     * @return ($simple is true ? MariaDBSimpleDMLQueryBuilder : MariaDBDMLQueryBuilder) New DatabaseDMLQueryBuilder object instance
     */
    public function get_new_dml_query_builder_object(bool $simple = TRUE): MariaDBDMLQueryBuilder|MariaDBSimpleDMLQueryBuilder
    {
        $querybuilder = new MariaDBDMLQueryBuilder();
        if ($simple === TRUE)
        {
            return new MariaDBSimpleDMLQueryBuilder($querybuilder, $this->get_query_escaper_object());
        }
        else
        {
            return $querybuilder;
        }
    }

}

?>
