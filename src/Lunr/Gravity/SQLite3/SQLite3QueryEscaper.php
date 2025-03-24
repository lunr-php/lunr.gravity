<?php

/**
 * SQLite3 query escaper class.
 *
 * SPDX-FileCopyrightText: Copyright 2012 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\SQLite3;

use Lunr\Gravity\DatabaseQueryEscaper;
use Lunr\Gravity\DatabaseStringEscaperInterface;

/**
 * This class provides SQLite3 specific escaping methods for SQL query parts.
 */
class SQLite3QueryEscaper extends DatabaseQueryEscaper
{

    /**
     * The left identifier delimiter.
     * @var string
     */
    protected const IDENTIFIER_DELIMITER_L = '"';

    /**
     * The right identifier delimiter.
     * @var string
     */
    protected const IDENTIFIER_DELIMITER_R = '"';

    /**
     * Constructor.
     *
     * @param DatabaseStringEscaperInterface $escaper Instance of a class implementing the DatabaseStringEscaperInterface.
     */
    public function __construct($escaper)
    {
        parent::__construct($escaper);
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        parent::__destruct();
    }

    /**
     * Define and escape input as value.
     *
     * @param mixed  $value     Input
     * @param string $collation Collation name
     * @param string $charset   Unused with SQLite
     *
     * @return string $return Defined and escaped value
     */
    public function value($value, $collation = '', $charset = ''): string
    {
        return trim($this->collate('\'' . $this->escaper->escape_string($value) . '\'', $collation));
    }

    /**
     * Not supported by sqlite. Returns the same as value.
     *
     * @param mixed  $value     Input
     * @param string $collation Collation name
     * @param string $charset   Unused with SQLite
     *
     * @return string $return Defined, escaped and unhexed value
     */
    public function hexvalue($value, $collation = '', $charset = ''): string
    {
        return $this->value($value, $collation, $charset);
    }

    /**
     * Define and escape input as a hexadecimal value.
     *
     * @param mixed  $value     Input
     * @param string $match     Whether to match forward, backward or both
     * @param string $collation Collation name
     * @param string $charset   Unused with SQLite
     *
     * @return string $return Defined, escaped and unhexed value
     */
    public function likevalue($value, $match = 'both', $collation = '', $charset = ''): string
    {
        switch ($match)
        {
            case 'forward':
                $string = '\'' . $this->escaper->escape_string($value) . '%\'';
                break;
            case 'backward':
                $string = '\'%' . $this->escaper->escape_string($value) . '\'';
                break;
            case 'both':
            default:
                $string = '\'%' . $this->escaper->escape_string($value) . '%\'';
                break;
        }

        return trim($this->collate($string, $collation));
    }

    /**
     * Define and escape input as index hint.
     *
     * @param string $keyword Whether to use INDEXED BY or NOT INDEXED the index/indices
     * @param array  $indices Array of indices
     * @param string $for     Unused with SQLite
     *
     * @return string|null $return NULL for invalid indices, escaped string otherwise.
     */
    public function index_hint(string $keyword, array $indices, string $for = ''): ?string
    {
        if (empty($indices))
        {
            return NULL;
        }

        $keyword = strtoupper($keyword);

        $validKeywords = [ 'INDEXED BY', 'NOT INDEXED' ];

        if (!in_array($keyword, $validKeywords))
        {
            $keyword = 'INDEXED BY';
        }

        $indices = array_map([ $this, 'escape_location_reference' ], $indices);
        $indices = implode(', ', $indices);

        return $keyword . ' ' . $indices;
    }

}

?>
