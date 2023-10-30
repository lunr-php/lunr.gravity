<?php

/**
 * Database string escaper interface.
 *
 * SPDX-FileCopyrightText: Copyright 2023 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity;

/**
 * This interface defines the query escape primitives.
 */
interface DatabaseStringEscaperInterface
{

    /**
     * Return a new instance of a QueryEscaper object.
     *
     * @return DatabaseQueryEscaper New DatabaseQueryEscaper object instance
     */
    public function get_query_escaper_object(): DatabaseQueryEscaper;

    /**
     * Escape a string to be used in a SQL query.
     *
     * @param string $string The string to escape
     *
     * @return string The escaped string
     */
    public function escape_string(string $string): string;

}

?>
