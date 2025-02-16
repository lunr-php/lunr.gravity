<?php

/**
 * This file contains the MockMysqliResult class.
 *
 * SPDX-FileCopyrightText: Copyright 2013 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MySQL\Tests;

use MySQLi_result;

/**
 * This class is a wrapper around MySQLi_result.
 */
class MockMySQLiResult
{

    /**
     * Instance of a real MySQLi_result class.
     * @var MySQLi_result
     */
    private $mysqliResult;

    /**
     * Constructor.
     *
     * @param MySQLi_result $mysqliResult Instance of the MySQLi_result class
     */
    public function __construct($mysqliResult)
    {
        $this->mysqliResult = $mysqliResult;
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        unset($this->mysqliResult);
    }

    /**
     * Forward non-mocked methods to the MySQLi class.
     *
     * @param string $method    Method name
     * @param array  $arguments Arguments to that method
     *
     * @return mixed $return Return value of the respective MySQLi method.
     */
    public function __call($method, $arguments)
    {
        return call_user_func_array([ $this->mysqliResult, $method ], $arguments);
    }

    /**
     * Fake property access to the MySQLi class.
     *
     * @param string $name Property name
     *
     * @return mixed $return Property value.
     */
    public function __get($name)
    {
        switch ($name)
        {
            case 'num_rows':
                return 5;
            default:
                return $this->mysqliResult->{$name};
        }
    }

}

?>
