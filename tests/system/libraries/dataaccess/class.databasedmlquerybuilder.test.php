<?php

/**
 * This file contains the DatabaseDMLQueryBuilderTest class.
 *
 * PHP Version 5.3
 *
 * @category   Libraries
 * @package    DataAccess
 * @subpackage Tests
 * @author     M2Mobi <info@m2mobi.com>
 * @author     Heinz Wiesinger <heinz@m2mobi.com>
 */

namespace Lunr\Libraries\DataAccess;
use PHPUnit_Framework_TestCase;
use ReflectionClass;

/**
 * This class contains common setup routines, providers
 * and shared attributes for testing the DatabaseDMLQueryBuilder class.
 *
 * @category   Libraries
 * @package    DataAccess
 * @subpackage Tests
 * @author     Heinz Wiesinger <heinz@m2mobi.com>
 * @covers     Lunr\Libraries\DataAccess\DatabaseDMLQueryBuilder
 */
abstract class DatabaseDMLQueryBuilderTest extends PHPUnit_Framework_TestCase
{

    /**
     * Instance of the DatabaseQueryBuilder class.
     * @var DatabaseDMLQueryBuilder
     */
    protected $builder;

    /**
     * Reflection instance of the DatabaseDMLQueryBuilder class.
     * @var ReflectionClass
     */
    protected $builder_reflection;

    /**
     * TestCase Constructor.
     */
    public function setUp()
    {
        $this->builder = $this->getMockForAbstractClass('Lunr\Libraries\DataAccess\DatabaseDMLQueryBuilder');

        $this->builder_reflection = new ReflectionClass('Lunr\Libraries\DataAccess\DatabaseDMLQueryBuilder');
    }

    /**
     * TestCase Destructor.
     */
    public function tearDown()
    {
        unset($this->builder);
        unset($this->builder_reflection);
    }

    /**
     * Unit test data provider for column names.
     *
     * @return array $cols Array of column names and expected escaped values.
     */
    public function columnNameProvider()
    {
        $cols   = array();
        $cols[] = array('*', '*');
        $cols[] = array('table.*', '`table`.*');
        $cols[] = array('col', '`col`');
        $cols[] = array('table.col', '`table`.`col`');
        $cols[] = array('db.table.col', '`db`.`table`.`col`');

        return $cols;
    }

}

?>