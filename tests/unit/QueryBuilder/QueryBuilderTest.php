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
use Charon\Db\Query\Clause\Expression;
use Charon\Db\Query\Clause\From;
use Charon\Db\Query\Clause\Group;
use Charon\Db\Query\Clause\Join;
use Charon\Db\Query\Clause\Order;
use Charon\Db\Query\QueryBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Column::class)]
#[CoversClass(From::class)]
#[CoversClass(Condition::class)]
#[CoversClass(Join::class)]
#[CoversClass(Group::class)]
#[CoversClass(Order::class)]
#[CoversClass(Expression::class)]
#[CoversClass(QueryBuilder::class)]
#[\PHPUnit\Framework\Attributes\Group('unit tests')]
class QueryBuilderTest extends TestCase
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

    public function testBasicSelectDistinct(): void {
        $qb = new QueryBuilder($this->conn);

        $query = $qb
            ->select('id', 'name')
            ->distinct()
            ->from('users')
            ->compile();

        self::assertEquals('SELECT DISTINCT id, name FROM users', $query);
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

    public function testSelectWithLeftJoin(): void {
        $qb = new QueryBuilder($this->conn);

        $query = $qb
            ->select('u.id', 'u.name')
            ->from('users', 'u')
            ->leftJoin('u', 'posts', 'p', 'u.id = p.id')
            ->compile();

        self::assertEquals('SELECT u.id, u.name FROM users AS u LEFT JOIN posts p ON u.id = p.id', $query);
    }

    public function testSelectWithRightJoin(): void {
        $qb = new QueryBuilder($this->conn);

        $query = $qb
            ->select('u.id', 'u.name')
            ->from('users', 'u')
            ->rightJoin('u', 'posts', 'p', 'u.id = p.id')
            ->compile();

        self::assertEquals('SELECT u.id, u.name FROM users AS u RIGHT JOIN posts p ON u.id = p.id', $query);
    }

    public function testSelectWithInnerJoin(): void {
        $qb = new QueryBuilder($this->conn);

        $query = $qb
            ->select('u.id', 'u.name')
            ->from('users', 'u')
            ->join('u', 'posts', 'p', 'u.id = p.id')
            ->compile();

        self::assertEquals('SELECT u.id, u.name FROM users AS u INNER JOIN posts p ON u.id = p.id', $query);
    }

    public function testSelectWithJoinsAndConditions(): void {
        $qb = new QueryBuilder($this->conn);

        $query = $qb
            ->select('u.id', 'u.name')
            ->from('users', 'u')
            ->leftJoin('u', 'posts', 'p', 'u.id = p.id')
            ->where('p.id', 1)
            ->compile();

        self::assertEquals(
            'SELECT u.id, u.name FROM users AS u LEFT JOIN posts p ON u.id = p.id WHERE p.id = 1',
            $query
        );
    }

    public function testSelectWithHaving(): void {
        $qb = new QueryBuilder($this->conn);

        $query = $qb
            ->select('u.id', 'u.name')
            ->from('users', 'u')
            ->leftJoin('u', 'posts', 'p', 'u.id = p.id')
            ->groupBy('u.id')
            ->having('COUNT(p.id) > 1')
            ->compile();

        self::assertEquals(
            'SELECT u.id, u.name FROM users AS u '
                    . 'LEFT JOIN posts p ON u.id = p.id '
                    . 'GROUP BY u.id HAVING COUNT(p.id) > 1',
            $query
        );
    }

    public function testSelectWithAndHaving(): void {
        $qb = new QueryBuilder($this->conn);

        $query = $qb
            ->select('u.id', 'u.name')
            ->from('users', 'u')
            ->leftJoin('u', 'posts', 'p', 'u.id = p.id')
            ->groupBy('u.id')
            ->having('COUNT(u.name) > 1')
            ->andHaving('COUNT(p.id) > 1')
            ->compile();

        self::assertEquals(
            'SELECT u.id, u.name FROM users AS u '
                    . 'LEFT JOIN posts p ON u.id = p.id '
                    . 'GROUP BY u.id HAVING COUNT(u.name) > 1 AND COUNT(p.id) > 1',
            $query
        );
    }

    public function testInsert(): void {
        $qb = new QueryBuilder($this->conn);

        $query = $qb
            ->insert('users')
            ->values(
                [
                    'name' => "'John'",
                    'surname' => "'Doe'"
                ]
            )
            ->compile();

        self::assertEquals('INSERT INTO users (name, surname) VALUES (\'John\', \'Doe\')', $query);
    }

    public function testInsertWithPlaceholder(): void {
        $qb = new QueryBuilder($this->conn);

        $query = $qb
            ->insert('users')
            ->values(
                [
                    'name' => '?',
                    'surname' => '?'
                ]
            )
            ->compile();

        self::assertEquals('INSERT INTO users (name, surname) VALUES (?, ?)', $query);
    }

    public function testSimpleDelete(): void {
        $qb = new QueryBuilder($this->conn);

        $query = $qb
            ->delete('users')
            ->compile();

        self::assertEquals('DELETE FROM users', $query);
    }

    public function testDeleteWithWhere(): void {
        $qb = new QueryBuilder($this->conn);

        $query = $qb
            ->delete('users')
            ->where('id', 1)
            ->compile();

        self::assertEquals('DELETE FROM users WHERE id = 1', $query);
    }
}
