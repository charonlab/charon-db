<?php

/*
 * This file is part of the charonlab/charon-db.
 *
 * Copyright (C) 2023-2024 Charon Lab Development Team
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */

namespace Charon\Test\Unit\QueryBuilder;

use Charon\Db\Connection;
use Charon\Db\Query\Clause\Column;
use Charon\Db\Query\Clause\Condition;
use Charon\Db\Query\Clause\From;
use Charon\Db\Query\Clause\Group;
use Charon\Db\Query\Clause\Order;
use Charon\Db\Query\QueryBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Column::class)]
#[CoversClass(From::class)]
#[CoversClass(Condition::class)]
#[CoversClass(Group::class)]
#[CoversClass(Order::class)]
#[CoversClass(QueryBuilder::class)]
#[\PHPUnit\Framework\Attributes\Group('unit tests')]
class QueryBuilderSelectTest extends TestCase
{
    /** @var \Charon\Db\Connection&\PHPUnit\Framework\MockObject\MockObject $conn */
    private Connection $conn;

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function setUp(): void {
        $this->conn = self::createMock(Connection::class);
    }

    public function testBasicSelect(): void {
        $qb = new QueryBuilder($this->conn);

        $query = $qb
            ->select('id', 'name')
            ->from('users')
            ->compile();

        self::assertEquals('SELECT id, name FROM users', $query);
    }

    public function testSelectWithAliases(): void {
       $qb = new QueryBuilder($this->conn);

       $query = $qb
           ->select('u.id')
           ->from('users', 'u')
           ->compile();

       self::assertEquals('SELECT u.id FROM users AS u', $query);
    }

    public function testSelectOrderBy(): void {
        $qb = new QueryBuilder($this->conn);

        $query = $qb
            ->select('id', 'name')
            ->from('users')
            ->orderBy('id', 'DESC')
            ->compile();

        self::assertEquals('SELECT id, name FROM users ORDER BY id DESC', $query);
    }

    public function testSelectGroupBy(): void {
        $qb = new QueryBuilder($this->conn);

        $query = $qb
            ->select('id', 'name')
            ->from('users')
            ->groupBy('id')
            ->compile();

        self::assertEquals('SELECT id, name FROM users GROUP BY id', $query);
    }

    public function testSelectWithSimpleWhere(): void {
        $qb = new QueryBuilder($this->conn);

        $query = $qb
            ->select('id', 'name')
            ->from('users')
            ->where('id', 1)
            ->compile();

        self::assertEquals('SELECT id, name FROM users WHERE id = 1', $query);
    }

}