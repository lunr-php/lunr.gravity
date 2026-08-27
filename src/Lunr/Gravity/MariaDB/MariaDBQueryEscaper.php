<?php

/**
 * MariaDB query escaper class.
 *
 * SPDX-FileCopyrightText: Copyright 2026 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MariaDB;

use Lunr\Gravity\MySQL\MySQLQueryEscaper;

/**
 * This class provides MariaDB specific escaping methods for SQL query parts.
 */
class MariaDBQueryEscaper extends MySQLQueryEscaper
{

    /**
     * Define and escape input as a row alias for the VALUES clause.
     *
     * MariaDB does not support row aliases on the VALUES clause (see MDEV-29919),
     * so the alias is silently dropped here.
     *
     * @param string $alias Alias name for the inserted row
     *
     * @return string $return Always an empty string, since MariaDB doesn't support this feature
     */
    public function row_alias(string $alias): string
    {
        return '';
    }

}

?>
