<?php

/**
 * This file contains the MySQLConnectionEscapeTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2021 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MySQL\Tests;

use Lunr\Gravity\MySQL\MySQLCanonicalQuery;
use Lunr\Halo\LunrBaseTestCase;

/**
 * This class contains unit tests for MySQLCanonicalQuery.
 *
 * @covers Lunr\Gravity\MySQL\MySQLCanonicalQuery
 */
abstract class MySQLCanonicalQueryTestCase extends LunrBaseTestCase
{

    /**
     * Instance of the tested class.
     * @var MySQLCanonicalQuery
     */
    protected MySQLCanonicalQuery $class;

    /**
     * TestCase Constructor.
     */
    public function setUp(): void
    {
         $this->class = new MySQLCanonicalQuery('');

         parent::baseSetUp($this->class);
    }

    /**
     * TestCase Destructor.
     */
    public function tearDown(): void
    {
        unset($this->class);

        parent::tearDown();
    }

    /**
     * Unit Test Data Provider.
     *
     * @return array $dataProvider Array of data values.
     */
    public function findPositionsDataProvider(): array
    {
        $dataProvider                               = [];
        $dataProvider['first argument not string']  = [[[ '013456789013456789','23','56' ],[]], []];
        $dataProvider['second argument not string'] = [[[ '012346789012346789','23','56' ],[]], [[ 2,17 ]]];
        $dataProvider['both arguments string']      = [[[ '01234567890123456789','23','56' ],[]], [[ 2,6 ],[ 12,16 ]]];
        $dataProvider['both arguments not string']  = [[[ '01234567890123456789','bb','cc' ],[]], []];
        $dataProvider['no second argument']         = [[[ '01234567890123456789','56','' ],[]], [[ 5,6 ],[ 15,16 ]]];
        $dataProvider['no first argument']          = [[[ '01234567890123456789','','56' ],[]], []];
        $dataProvider['single no second arg']       = [[[ '01234567890123456789','5','' ],[]], [[ 5,5 ],[ 15,15 ]]];
        $dataProvider['ignore positions']           = [[[ '01234567890123456789','23','56' ],[[ 1,8 ]]], [[ 12,16 ]]];

        return $dataProvider;
    }

    /**
     * Unit Test Data Provider.
     *
     * @return array $dataProvider Array of data values.
     */
    public function removeEolBlankSpacesDataProvider(): array
    {
        $dataProvider   = [];
        $dataProvider[] = [ '  SELECT     *   FROM `table`   ','SELECT * FROM `table`' ];
        $dataProvider[] = [ "SELECT * \nFROM `table`",'SELECT * FROM `table`' ];
        $dataProvider[] = [ "SELECT * \r\nFROM `table`",'SELECT * FROM `table`' ];
        $dataProvider[] = [ "SELECT * \rFROM `table`",'SELECT * FROM `table`' ];

        return $dataProvider;
    }

    /**
     * Unit Test Data Provider.
     *
     * @return array $dataProvider Array of data values.
     */
    public function isNumericValueDataProvider(): array
    {
        $dataProvider   = [];
        $dataProvider[] = [[ 'value=234567 AND',6 ],[ TRUE,11 ]];
        $dataProvider[] = [[ 'value=0x47 AND',6 ],[ TRUE,9 ]];
        $dataProvider[] = [[ 'value=1.245 AND',6 ],[ TRUE,10 ]];
        $dataProvider[] = [[ 'value=3.82384E-11 AND',6 ],[ TRUE,16 ]];

        return $dataProvider;
    }

    /**
     * Unit Test Data Provider.
     *
     * @return array $dataProvider Array of data values.
     */
    public function updatePositionsDataProvider(): array
    {
        $dataProvider   = [];
        $dataProvider[] = [[[[ 3,10 ],[ 25,30 ],[ 40,50 ]],15,5 ],[[ 3,10 ],[ 20,25 ],[ 35,45 ]]];

        return $dataProvider;
    }

    /**
     * Unit Test Data Provider.
     *
     * @return array $dataProvider Array of data values.
     */
    public function findDigitDataProvider(): array
    {
        $dataProvider   = [];
        $dataProvider[] = [[[ 'SELECT * FROM `table1` WHERE `value1`="teste" AND `value2`=12', 22 ],[[ 29,36 ],[ 50,57 ]]],59 ];
        $dataProvider[] = [[[ 'SELECT * FROM `table1` WHERE `value1`="teste" AND `value2`="A"', 22 ],[[ 29,36 ],[ 50,57 ]]],NULL ];

        return $dataProvider;
    }

    /**
     * Unit Test Data Provider.
     *
     * @return array $dataProvider Array of data values.
     */
    public function jumpIgnoreDataProvider(): array
    {
        $dataProvider   = [];
        $dataProvider[] = [[ 0,[[ 0,10 ],[ 10,100 ],[ 102,110 ]]], 101 ];

        return $dataProvider;
    }

    /**
     * Unit Test Data Provider.
     *
     * @return array $dataProvider Array of data values.
     */
    public function isNegativeNumberDataProvider(): array
    {
        $dataProvider   = [];
        $dataProvider[] = [[ 'value=123',6 ], FALSE ];
        $dataProvider[] = [[ 'value=-123',7 ], TRUE ];
        $dataProvider[] = [[ 'value=?-123',8 ], FALSE ];

        return $dataProvider;
    }

    /**
     * Unit Test Data Provider.
     *
     * @return array $dataProvider Array of data values.
     */
    public function replaceNumericDataProvider(): array
    {
        $dataProvider   = [];
        $dataProvider[] = [[[ 'value=12 and','?' ],NULL ],'value=? and' ];
        $dataProvider[] = [[[ 'value=0x24 and','?' ],NULL ],'value=? and' ];
        $dataProvider[] = [[[ 'value=-12 and','?' ],NULL ],'value=? and' ];
        $dataProvider[] = [[[ 'value=1.24 and','?' ],NULL ],'value=? and' ];
        $dataProvider[] = [[[ 'value=-1.24 and','?' ],NULL ],'value=? and' ];
        $dataProvider[] = [[[ 'value=3.8E-11 and','?' ],NULL ],'value=? and' ];
        $dataProvider[] = [[[ 'value1=123456 and value2=123456 /*! 123 */','?' ],[[ 32,41 ]]],'value1=? and value2=? /*! 123 */' ];

        return $dataProvider;
    }

    /**
     * Unit Test Data Provider.
     *
     * @return array $dataProvider Array of data values.
     */
    public function replaceBetweenDataProvider(): array
    {
        $dataProvider                       = [];
        $dataProvider['replace jump'][0]    = [[ 'SELECT * FROM value1="ignore" AND value2="B"', '"', '"', '"?"',TRUE ],[[ 21,28 ]]];
        $dataProvider['replace jump'][1]    = 'SELECT * FROM value1="ignore" AND value2="?"';
        $dataProvider['replace no jump'][0] = [[ 'SELECT * FROM value1="ignore" AND value2="B"', '"', '"', '"?"',TRUE ],NULL ];
        $dataProvider['replace no jump'][1] = 'SELECT * FROM value1="?" AND value2="?"';

        return $dataProvider;
    }

    /**
     * Unit Test Data Provider.
     *
     * @return array $dataProvider Array of data values.
     */
    public function canonicalQueryDataProvider(): array
    {
        $path = TEST_STATICS . '/Gravity/Database/MySQL/';

        $dataProvider['select']                            = [
            $path . 'input_select.sql',
            $path . 'output_select.sql',
        ];
        $dataProvider['update']                            = [
            $path . 'input_update.sql',
            $path . 'output_update.sql',
        ];
        $dataProvider['create']                            = [
            $path . 'input_create.sql',
            $path . 'output_create.sql',
        ];
        $dataProvider['insert single row']                 = [
            $path . 'input_insert_single_row.sql',
            $path . 'output_insert_single_row.sql',
        ];
        $dataProvider['insert multi-rows']                 = [
            $path . 'input_insert_multi_rows.sql',
            $path . 'output_insert_multi_rows.sql',
        ];
        $dataProvider['insert different rows']             = [
            $path . 'input_insert_different_rows.sql',
            $path . 'output_insert_different_rows.sql',
        ];
        $dataProvider['insert no rows']                    = [
            $path . 'input_insert_no_rows.sql',
            $path . 'output_insert_no_rows.sql',
        ];
        $dataProvider['insert value or null multi-rows']   = [
            $path . 'input_insert_value_or_null_multirows.sql',
            $path . 'output_insert_value_or_null_multirows.sql',
        ];
        $dataProvider['insert null diff. case multi-rows'] = [
            $path . 'input_insert_null_diff_case_multi_rows.sql',
            $path . 'output_insert_null_diff_case_multi_rows.sql',
        ];
        $dataProvider['replace single row']                = [
            $path . 'input_replace_single_row.sql',
            $path . 'output_replace_single_row.sql',
        ];
        $dataProvider['replace multi-rows']                = [
            $path . 'input_replace_multi_rows.sql',
            $path . 'output_replace_multi_rows.sql',
        ];
        $dataProvider['upserts single row']                = [
            $path . 'input_upserts_single_row.sql',
            $path . 'output_upserts_single_row.sql',
        ];
        $dataProvider['upserts multi-rows']                = [
            $path . 'input_upserts_multi_rows.sql',
            $path . 'output_upserts_multi_rows.sql',
        ];
        $dataProvider['upserts function multi-rows']       = [
            $path . 'input_upserts_function_multi_rows.sql',
            $path . 'output_upserts_function_multi_rows.sql',
        ];
        $dataProvider['maxscalehints']                     = [
            $path . 'input_maxscalehints.sql',
            $path . 'output_maxscalehints.sql',
        ];
        $dataProvider['cte']                               = [
            $path . 'input_cte.sql',
            $path . 'output_cte.sql',
        ];

        return $dataProvider;
    }

    /**
     * Unit Test Data Provider.
     *
     * @return array $dataProvider Array of data values.
     */
    public function findNextDataProvider(): array
    {
        $dataProvider                      = [];
        $dataProvider['find']              = [[[ 'VALUES (?) , (?)',',',4,NULL ],[]],11 ];
        $dataProvider['find ignore char']  = [[[ ' ,(?),(?)',',',0,[ ' ' ]],[]],1 ];
        $dataProvider['found first index'] = [[[ ',(?),(?)',',',0,[ ' ' ]],[]],0 ];
        $dataProvider['offset']            = [[[ ',(?) ,(?)',',',4,[ ' ' ]],[]],5 ];
        $dataProvider['find not ignore']   = [[[ 'VALUES (?) , (?)',',',4,[ ' ' ]],[]],NULL ];
        $dataProvider['not found']         = [[[ '(?,?) ON ',',',4,NULL ],[]],NULL ];

        return $dataProvider;
    }

    /**
     * Unit Test Data Provider.
     *
     * @return array $dataProvider Array of data values.
     */
    public function getBetweenDelimiterDataProvider(): array
    {
        $dataProvider                      = [];
        $dataProvider['simple']            = [[ ' (?,?,"?") , (?,?,"?") ','(',')',0,[ ' ' ]],[ 1,9 ]];
        $dataProvider['offset']            = [[ 'values (?,?,"?") , (?,?,"?") ','(',')',18,[ ' ' ]],[ 19,27 ]];
        $dataProvider['delimiters inside'] = [[ 'values (COALESCE(?,"?"),?,"?") ','(',')',6,[ ' ' ]],[ 7,29 ]];
        $dataProvider['not found']         = [[ 'values (?,?,"?") , (?,?,"?") ','{','}',6,[ ' ' ]],NULL ];

        return $dataProvider;
    }

    /**
     * Unit Test Data Provider.
     *
     * @return array $dataProvider Array of data values.
     */
    public function collapseMultiRowInsertsDataProvider(): array
    {
        $path = TEST_STATICS . '/Gravity/Database/MySQL/';

        $dataProvider['insert single row']    = [
            $path . 'input_collapse_insert_single_row.sql',
            $path . 'output_collapse_insert_single_row.sql',
        ];
        $dataProvider['insert multi-rows']    = [
            $path . 'input_collapse_insert_multi_rows.sql',
            $path . 'output_collapse_insert_multi_rows.sql',
        ];
        $dataProvider['insert no rows']       = [
            $path . 'input_collapse_insert_no_rows.sql',
            $path . 'output_collapse_insert_no_rows.sql',
        ];
        $dataProvider['insert different row'] = [
            $path . 'input_collapse_insert_different_row.sql',
            $path . 'output_collapse_insert_different_row.sql',
        ];
        $dataProvider['insert function']      = [
            $path . 'input_collapse_insert_function_multi_rows.sql',
            $path . 'output_collapse_insert_function_multi_rows.sql',
        ];
        $dataProvider['replace']              = [
            $path . 'input_collapse_replace_multi_rows.sql',
            $path . 'output_collapse_replace_multi_rows.sql',
        ];
        $dataProvider['upserts single row']   = [
            $path . 'input_collapse_upserts_single_row.sql',
            $path . 'output_collapse_upserts_single_row.sql',
        ];
        $dataProvider['upserts multi-rows']   = [
            $path . 'input_collapse_upserts_multi_rows.sql',
            $path . 'output_collapse_upserts_multi_rows.sql',
        ];
        $dataProvider['upserts function']     = [
            $path . 'input_collapse_upserts_function.sql',
            $path . 'output_collapse_upserts_function.sql',
        ];
        $dataProvider['select']               = [
            $path . 'input_collapse_select.sql',
            $path . 'output_collapse_select.sql',
        ];

        return $dataProvider;
    }

}

?>
