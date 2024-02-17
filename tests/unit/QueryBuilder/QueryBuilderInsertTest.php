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
use Charon\Db\Query\QueryBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[CoversClass(QueryBuilder::class)]
#[Group('unit tests')]
class QueryBuilderInsertTest extends TestCase
{
    /** @var \Charon\Db\Connection&\PHPUnit\Framework\MockObject\MockObject $conn */
    private Connection $conn;

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function setUp(): void {
        $this->conn = self::createMock(Connection::class);
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
}