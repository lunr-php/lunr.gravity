<?php

/**
 * This file contains the TransactionalMariaDBAccessObjectBaseTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2023 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MariaDB\Tests;

/**
 * This class contains the tests for the TransactionalMariaDBAccessObject class.
 *
 * @covers Lunr\Gravity\MariaDB\TransactionalMariaDBAccessObject
 */
class TransactionalMariaDBAccessObjectBaseTest extends TransactionalMariaDBAccessObjectTestCase
{

    /**
     * Test that begin_transaction() is called on the DB when the method is called.
     *
     * @covers \Lunr\Gravity\MariaDB\TransactionalMariaDBAccessObject::begin_transaction
     */
    public function testBeginTransactionIsCalled(): void
    {
        $this->db->expects($this->once())
                 ->method('begin_transaction');

        $this->class->begin_transaction();
    }

    /**
     * Test that rollback_transaction() is called on the DB when the method is called.
     *
     * @covers \Lunr\Gravity\MariaDB\TransactionalMariaDBAccessObject::rollback_transaction
     */
    public function testRollbackTransactionIsCalled(): void
    {
        $this->db->expects($this->once())
                 ->method('rollback');

        $this->class->rollback_transaction();
    }

    /**
     * Test that commit_transaction() is called on the DB when the method is called.
     *
     * @covers \Lunr\Gravity\MariaDB\TransactionalMariaDBAccessObject::commit_transaction
     */
    public function testCommitTransactionIsCalled(): void
    {
        $this->db->expects($this->once())
                 ->method('commit');

        $this->class->commit_transaction();
    }

    /**
     * Test that end_transaction() is called on the DB when the method is called.
     *
     * @covers \Lunr\Gravity\MariaDB\TransactionalMariaDBAccessObject::end_transaction
     */
    public function testEndTransactionIsCalled(): void
    {
        $this->db->expects($this->once())
                 ->method('end_transaction');

        $this->class->end_transaction();
    }

}

?>
