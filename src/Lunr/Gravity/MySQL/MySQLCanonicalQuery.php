<?php

/**
 * MySQL canonical query class.
 *
 * SPDX-FileCopyrightText: Copyright 2021 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MySQL;

/**
 * This class returns the Canonicalized query.
 */
class MySQLCanonicalQuery
{

    /**
     * Query to canonicalize.
     * @var string
     */
    private readonly string $query;

    /**
     * Canonicalized query.
     * @var string
     */
    private readonly string $canonicalQuery;

    /**
     * List of index ranges to ignore.
     * @var array
     */
    private $ignorePositions;

    /**
     * Constructor.
     *
     * @param string $query Executed query
     */
    public function __construct(string $query)
    {
        $this->query = $query;
    }

    /**
     * __toString.
     *
     * @return string $returns The executed query canonicalized
     */
    public function __toString(): string
    {
        return $this->get_canonical_query();
    }

    /**
     * Get the executed query canonicalized.
     *
     * @return string $canonical_query The executed query canonicalized
     */
    public function get_canonical_query(): string
    {
        if (isset($this->canonicalQuery) === TRUE)
        {
            return $this->canonicalQuery;
        }

        $this->ignorePositions = [];

        $tmpQuery = $this->query;

        $tmpQuery = $this->replace_between($tmpQuery, '#', PHP_EOL, '');
        $tmpQuery = $this->replace_between($tmpQuery, '--', PHP_EOL, '');

        $tmpQuery = $this->remove_eol_blank_spaces($tmpQuery);

        $this->add_ignore_positions($this->find_positions($tmpQuery, '/*M', '*/'));
        $this->add_ignore_positions($this->find_positions($tmpQuery, '/*!', '*/'));
        $this->add_ignore_positions($this->find_positions($tmpQuery, '`', '`'));
        $this->add_ignore_positions($this->find_positions($tmpQuery, '\\'));

        $tmpQuery = $this->replace_between($tmpQuery, '/*', '*/', '');
        $tmpQuery = $this->replace_between($tmpQuery, '"', '"', '"?"', TRUE);
        $tmpQuery = $this->replace_between($tmpQuery, '\'', '\'', '\'?\'', TRUE);

        $tmpQuery = $this->replace_numeric($tmpQuery, '?');

        $tmpQuery = $this->collapse_multirows($tmpQuery);

        $this->canonicalQuery = $this->remove_eol_blank_spaces($tmpQuery);

        return $this->canonicalQuery;
    }

    /**
     * Replaces text in between two strings
     *
     * @param string $string      Input string to replace
     * @param string $from        Input string to start replacing
     * @param string $to          Input string to end replacing
     * @param string $replace     Input string to replace with
     * @param bool   $addToIgnore Input bool to decide if add to ignore list
     *
     * @return string $string The provided string replaced
     */
    private function replace_between(string $string, string $from, string $to, string $replace, bool $addToIgnore = FALSE): string
    {
        $offset = 0;

        while ($offset < strlen($string))
        {
            $startPos = strpos($string, $from, $offset);

            if ($startPos === FALSE)
            {
                break;
            }

            $offset = $this->jump_ignore($startPos);
            if ($offset > $startPos)
            {
                continue;
            }

            $endPos = strpos($string, $to, ($startPos + 1));

            if ($endPos === FALSE)
            {
                $endPos = strlen($string) - 1;
            }

            $lenToRemove = ($endPos + strlen($to)) - $startPos;

            $string = substr_replace($string, $replace, $startPos, $lenToRemove);

            $this->ignorePositions = $this->update_positions($this->ignorePositions, $startPos, $lenToRemove - strlen($replace));

            if ($addToIgnore === TRUE)
            {
                $this->add_ignore_positions([[ $startPos, $startPos + strlen($replace) - 1 ]]);
            }

            $offset = $startPos + strlen($replace);
        }

        return $string;
    }

    /**
     * Search for all the index ranges in a string between two strings
     * if $to is empty searches only for $from position
     *
     * @param string      $string Input string to search
     * @param string      $from   Input string to search the start of range
     * @param string|null $to     Input string to search the end of range
     * @param int         $offset Input int index of where to start the search
     *
     * @return array $positions All ranges between $to and $from,
     *               if can't find $to returns empty,
     *               if can't find $from returns the end of string,
     *               if $to is empty returns all $from positions
     */
    private function find_positions(string $string, string $from, ?string $to = NULL, int $offset = 0): array
    {
        if (empty($from))
        {
            return [];
        }

        $positions = [];
        $end       = strlen($string);
        while ($offset < $end)
        {
            $tmpPosition = [ strpos($string, $from, $offset), NULL ];
            if ($tmpPosition[0] === FALSE)
            {
                break;
            }

            $jump = $this->jump_ignore($tmpPosition[0]);
            if ($jump > $tmpPosition[0])
            {
                $offset = $jump;
                continue;
            }

            $position = $tmpPosition;

            if (is_null($to) || $to === '')
            {
                $position[1] = $position[0] + strlen($from) - 1;
                $offset      = $position[1] + 1;
                $positions[] = $position;
                continue;
            }

            $position[1] = strpos($string, $to, ($position[0] + 1));

            if ($position[1] === FALSE)
            {
                $position[1] = strlen($string) - 1;
            }
            else
            {
                $position[1] = $position[1] + strlen($to) - 1;
            }

            $offset      = $position[1] + 1;
            $positions[] = $position;
        }

        return $positions;
    }

    /**
     * Removes blank space and end of line characters from string
     *
     * @param string $string Input string remove
     *
     * @return string $returns The $string without blank spaces and end of line characters
     */
    private function remove_eol_blank_spaces(string $string): string
    {
        return trim(preg_replace('/\s+/', ' ', $string));
    }

    /**
     * Add a range of indexes to $ignore_positions
     *
     * @param array $positions Input array with ranges of indexes
     * @return void
     */
    private function add_ignore_positions(array $positions): void
    {
        $this->ignorePositions = array_merge($this->ignorePositions, $positions);
        asort($this->ignorePositions);
        $this->ignorePositions = array_values($this->ignorePositions);
    }

    /**
     * Update all index ranges if start index
     *
     * @param array $positions Input array with ranges of indexes
     * @param int   $start     Input integer to start the update
     * @param int   $offset    Input integer with the amount to offset positions
     *
     * @return array returns $positions updated
     */
    private function update_positions(array $positions, int $start, int $offset): array
    {
        foreach ($positions as $k => $position)
        {
            $positions[$k][0] = ($position[0] < $start) ? $position[0] : $position[0] - $offset;
            $positions[$k][1] = ($position[1] < $start) ? $position[1] : $position[1] - $offset;
        }

        return $positions;
    }

    /**
     * Get the index position of the next digit from offset
     *
     * @param string $string Input string where to search for the next index position
     * @param int    $offset Input integer where to start to search
     *
     * @return int|null return Index of the next digit, NULL if not found
     */
    private function find_digit(string $string, int $offset): ?int
    {
        $end = strlen($string);
        for ($i = $offset; $i < $end; ++$i)
        {
            $jump = $this->jump_ignore($i);
            if ($jump > $i)
            {
                $i = $jump;
                continue;
            }

            if (ctype_digit($string[$i]) && ($i == 0 || (!ctype_alnum(substr($string, $i - 1, 1)) && substr($string, $i - 1, 1) != '_')))
            {
                return $i;
            }
        }

        return NULL;
    }

    /**
     * Replaces numeric value (Integer, Decimal, Hexadecimal, Exponential) with string
     *
     * @param string $string  Input string to replace
     * @param string $replace Input string to replace with
     *
     * @return string returns string with numeric values replaced
     */
    private function replace_numeric(string $string, string $replace): string
    {
        $end = strlen($string);
        for ($i = 0; $i < $end; ++$i)
        {
            $pos = $this->find_digit($string, $i);
            if (!isset($pos))
            {
                break;
            }

            $numberEnd = $this->is_numeric_value($string, $pos);

            $i = $numberEnd[1];

            if ($numberEnd[0] === FALSE)
            {
                continue;
            }

            if ($this->is_negative_number($string, $pos))
            {
                $pos--;
            }

            $replaceSize           = strlen($replace);
            $toReplaceLength       = $numberEnd[1] - $pos + 1;
            $string                = substr_replace($string, $replace, $pos, $toReplaceLength);
            $this->ignorePositions = $this->update_positions($this->ignorePositions, $pos, $toReplaceLength - $replaceSize);

            $i = $pos + $replaceSize;
        }

        return $string;
    }

    /**
     * Check if number is a negation
     *
     * @param string $string Input string to check
     * @param int    $i      Start index of the number position
     *
     * @return bool returns TRUE if finds the negation character and is not a subtraction, FALSE otherwise
     */
    private function is_negative_number(string $string, int $i): bool
    {
        $isNegativeNumber = FALSE;
        if ($i > 0 && $string[$i - 1] == '-')
        {
            $i--;
            // Possibly a negative number
            $isNegativeNumber = TRUE;
            for ($j = $i - 1; $j >= 0; $j--)
            {
                if (!ctype_space($string[$j]))
                {
                    /** If we find a previously converted value, we know that it
                     * is not a negation but a subtraction. */
                    $isNegativeNumber = ($string[$j] != '?');
                    break;
                }
            }
        }

        return $isNegativeNumber;
    }

    /**
     * Check if digit is a number and get the position of last number digit
     *
     * @param string $string Input string to check
     * @param int    $i      Start position of digit index
     *
     * @return array $return the last index of number and is number result
     */
    private function is_numeric_value(string $string, int $i): array
    {
        $allowHex      = FALSE;
        $isHexadecimal = (($string[$i] == '0'));

        $i++; //first number we already know is numeric
        $end      = strlen($string);
        $isNumber = TRUE;

        while ($i < $end)
        {
            if (!(ctype_digit($string[$i]) || ($allowHex && ctype_xdigit($string[$i]))))
            {
                if ($isHexadecimal == TRUE && strtolower($string[$i]) == 'x')
                {
                    $isHexadecimal = FALSE;
                    $allowHex      = TRUE;
                }
                elseif (strtolower($string[$i]) == 'e')
                {
                    $next = $i + 1;
                    // Possible scientific notation number
                    if ($next == $end || (!ctype_digit($string[$next]) && $string[$next] != '-'))
                    {
                        $i        = ++$next;
                        $isNumber = FALSE;
                        break;
                    }

                    // Skip over the minus if we have one
                    if ($string[$next] == '-')
                    {
                        $i++;
                    }
                }
                elseif ($string[$i] == '.')
                {
                    $next = $i + 1;
                    // Possible decimal number
                    if ($next != $end && !ctype_digit($string[$next]))
                    {
                        /** The fractional part of a decimal is optional in MariaDB. */
                        break;
                    }
                }
                else
                {
                    // If we have a non-text character, we treat it as a number
                    $isNumber = !ctype_alpha($string[$i]);
                    break;
                }
            }

            $i++;
        }

        return [ $isNumber, --$i ];
    }

    /**
     * Checks if index is in a position to ignore, and returns next position
     *
     * @param int $index Input integer to check if is between ranges
     *
     * @return int returns the position after the range to ignore, if not in range returns the provided index
     */
    private function jump_ignore(int $index): int
    {
        foreach ($this->ignorePositions as $position)
        {
            if ($position[0] <= $index && $position[1] >= $index)
            {
                $index = $position[1] + 1;
            }
        }

        return $index;
    }

    /**
     * Collapses multi-row insert into one
     *
     * @param string $string Input string with canonical query to collapse
     *
     * @return string returns the string with canonical_query without multi-row values
     */
    private function collapse_multirows(string $string): string
    {
        if (stripos($string, 'INSERT INTO') === FALSE && stripos($string, 'REPLACE INTO') === FALSE)
        {
            return $string;
        }

        $end    = strlen($string);
        $offset = $this->find_positions($string, 'VALUES');

        if (count($offset) === 0)
        {
            return $string;
        }

        $firstRowPosition = $this->get_between_delimiter($string, '(', ')', $offset[0][1] + 1, [ ' ' ]);

        if ($firstRowPosition === NULL)
        {
            return $string;
        }

        $firstRow = substr($string, $firstRowPosition[0], $firstRowPosition[1] - $firstRowPosition[0] + 1);

        $tmpString = $string;
        $i         = $firstRowPosition[1] + 1;
        while ($i < $end)
        {
            $nextRowStart = $this->find_next($tmpString, ',', $firstRowPosition[1] + 1, [ ' ' ]);
            if ($nextRowStart === NULL)
            {
                break;
            }

            $rowPosition = $this->get_between_delimiter($tmpString, '(', ')', $nextRowStart + 1, [ ' ' ]);
            if ($rowPosition === NULL)
            {
                return $string;
            }

            $row = substr($tmpString, $rowPosition[0], $rowPosition[1] - $rowPosition[0] + 1);

            if ($firstRow != $row)
            {
                return $string;
            }

            $tmpString = substr_replace($tmpString, '', $firstRowPosition[1] + 1, $rowPosition[1] - $firstRowPosition[1]);

            $end = strlen($tmpString);
            $i   = $firstRowPosition[1] + 1;
        }

        return $tmpString;
    }

    /**
     * Finds the next start and end index positions between two delimiters, ignoring delimiters in between
     *
     * @param string $string   Input string to search
     * @param string $startDel Input string with start delimiter
     * @param string $endDel   Input string with end delimiter
     * @param int    $offset   Input int with index to start the search
     * @param array  $ignore   Input array with chars to ignore until start position is found
     *
     * @return array|null returns the range position of the start and end delimiters, if is not found returns null
     */
    private function get_between_delimiter(string $string, string $startDel, string $endDel, int $offset = 0, array $ignore = []): ?array
    {
        $end                 = strlen($string);
        $delimiterStartIndex = NULL;
        $delimiterEndIndex   = NULL;
        for ($i = $offset; $i < $end; $i++)
        {
            if ($string[$i] === $startDel)
            {
                $delimiterStartIndex = $i;
                break;
            }
            elseif ($string[$i] == in_array($string[$i], $ignore))
            {
                continue;
            }
            elseif ($string[$i] != $startDel)
            {
                return NULL;
            }
        }

        if ($delimiterStartIndex === NULL)
        {
            return NULL;
        }

        $countP = 0;
        for ($i = $delimiterStartIndex + 1; $i < $end; $i++)
        {
            if ($string[$i] === $startDel)
            {
                $countP++;
            }
            elseif ($string[$i] === $endDel && $countP > 0)
            {
                $countP--;
            }
            elseif ($string[$i] === $endDel && $countP === 0)
            {
                $delimiterEndIndex = $i;
                break;
            }
        }

        if ($delimiterEndIndex === NULL)
        {
            return NULL;
        }

        return [ $delimiterStartIndex, $delimiterEndIndex ];
    }

    /**
     * Finds the next index position of a char
     *
     * @param string     $string     Input string to search
     * @param string     $char       Input string with character to find
     * @param int        $offset     Input string start position
     * @param array|null $ignoreChar Input array with characters to ignore, if null ignores all chars
     *
     * @return int|null returns the index position of the value found, if not found returns null
     */
    private function find_next(string $string, string $char, int $offset, ?array $ignoreChar = NULL): ?int
    {
        $end = strlen($string);

        while ($offset < $end)
        {
            $jump = $this->jump_ignore($offset);
            if ($jump > $offset)
            {
                $offset = $jump;
                continue;
            }

            if ($string[$offset] === $char)
            {
                return $offset;
            }

            if ($ignoreChar !== NULL && $string[$offset] == in_array($string[$offset], $ignoreChar))
            {
                $offset++;
                continue;
            }

            if ($string[$offset] !== $char && $ignoreChar !== NULL)
            {
                break;
            }

            $offset++;
        }

        return NULL;
    }

}

?>
