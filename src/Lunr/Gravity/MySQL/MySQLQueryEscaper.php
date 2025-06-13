<?php

/**
 * MySQL query escaper class.
 *
 * SPDX-FileCopyrightText: Copyright 2012 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MySQL;

use Lunr\Gravity\DatabaseQueryEscaper;
use Lunr\Gravity\DatabaseStringEscaperInterface;

/**
 * This class provides MySQL specific escaping methods for SQL query parts.
 *
 * @method string|null null_or_hexvalue($value)  Same as $this::hexvalue() but allowed to return null
 * @method string|null null_or_uuidvalue($value) Same as $this::uuidvalue() but allowed to return null
 * @method string|null null_or_geovalue($value)  Same as $this::geovalue() but allowed to return null
 */
class MySQLQueryEscaper extends DatabaseQueryEscaper
{

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
     * @param string $charset   Charset name
     *
     * @return string $return Defined and escaped value
     */
    public function value($value, $collation = '', $charset = ''): string
    {
        return trim($charset . ' ' . $this->collate('\'' . $this->escaper->escape_string($value) . '\'', $collation));
    }

    /**
     * Define and escape input as a hexadecimal value.
     *
     * @param mixed  $value     Input
     * @param string $collation Collation name
     * @param string $charset   Charset name
     *
     * @return string $return Defined, escaped and unhexed value
     */
    public function hexvalue($value, $collation = '', $charset = ''): string
    {
        return trim($charset . ' ' . $this->collate('UNHEX(\'' . $this->escaper->escape_string($value) . '\')', $collation));
    }

    /**
     * Define and escape input as a UUID value.
     *
     * @param mixed  $value     Input
     * @param string $collation Collation name
     * @param string $charset   Charset name
     *
     * @return string $return Defined, escaped and unhexed value
     */
    public function uuidvalue($value, $collation = '', $charset = ''): string
    {
        return trim($charset . ' ' . $this->collate('UNHEX(REPLACE(\'' . $this->escaper->escape_string($value) . '\', \'-\', \'\'))', $collation));
    }

    /**
     * Define and escape input as a hexadecimal value.
     *
     * @param mixed  $value     Input
     * @param string $match     Whether to match forward, backward or both
     * @param string $collation Collation name
     * @param string $charset   Charset name
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

        return trim($charset . ' ' . $this->collate($string, $collation));
    }

    /**
     * Define and escape input as a geometry value.
     *
     * @param string   $value Well-Known Text (WKT) representation of a geometry shape.
     * @param int|null $srid  The Spatial Reference System ID for the geometry value.
     *
     * @return string $return Defined and escaped geometric value.
     */
    public function geovalue($value, $srid = NULL): string
    {
        $args = [];

        $args[] = $this->value($value);

        if (!is_null($srid))
        {
            $args[] = intval($srid);
        }

        return 'ST_GeomFromText(' . join(', ', $args) . ')';
    }

    /**
     * Define and escape input as index hint.
     *
     * @param string $keyword Whether to USE, FORCE or IGNORE the index/indices
     * @param array  $indices Array of indices
     * @param string $for     Whether to use the index hint for JOIN, ORDER BY or GROUP BY
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

        $validKeywords = [ 'USE', 'IGNORE', 'FORCE' ];
        $validFor      = [ 'JOIN', 'ORDER BY', 'GROUP BY', '' ];

        if (!in_array($keyword, $validKeywords))
        {
            $keyword = 'USE';
        }

        if (!in_array($for, $validFor))
        {
            $for = '';
        }

        $indices = array_map([ $this, 'escape_location_reference' ], $indices);
        $indices = implode(', ', $indices);

        if ($for === '')
        {
            return $keyword . ' INDEX (' . $indices . ')';
        }

        return $keyword . ' INDEX FOR ' . $for . ' (' . $indices . ')';
    }

}

?>
