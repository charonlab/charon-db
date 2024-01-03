<?php

/*
 * This file is part of the abyss/database.
 *
 * Copyright (C) 2023-2024 Dominik Szamburski
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE.md file for details.
 */

namespace Abyss\Test\Unit\Query;

use Abyss\DBAL\Query\Grammars\Grammar;
use Abyss\DBAL\Query\QueryBuilder;
use Abyss\DBAL\Query\QueryBuilderInterface;
use Abyss\Test\Unit\Fixtures\DummyConnection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(QueryBuilder::class)]
class QueryBuilderTest extends TestCase
{
    private QueryBuilderInterface $builder;

    public function setUp(): void
    {
        $this->builder = new QueryBuilder(
            new DummyConnection()
        );

        parent::setUp();
    }

    public function testConstructor(): void
    {
        $this->assertInstanceOf(QueryBuilderInterface::class, $this->builder);
        $this->assertInstanceOf(Grammar::class, $this->builder->grammar);
    }

    public function testSelectWithDefaultValue(): void
    {
        $query = $this->builder->select();

        $this->assertInstanceOf(QueryBuilderInterface::class, $query);
        $this->assertEquals(['*'], $query->columns);
    }

    public function testSelectWithColumns(): void
    {
        $this->assertEquals(
            ['id', 'name'],
            $this->builder->select('id', 'name')->columns
        );
    }

    public function testFromWithoutAlias(): void
    {
        $this->assertEquals(
            'foo',
            $this->builder->from('foo')->from[0]
        );
    }

    public function testFromWithAlias(): void
    {
        $this->assertEquals(
            'foo AS f',
            $this->builder->from('foo', 'f')->from[0]
        );
    }

    public function testWhere(): void
    {
        $this->builder->where('foo', '=', 'bar');

        $this->assertCount(1, $this->builder->conditions);
        $this->assertEquals([
            'column' => 'foo',
            'operator' => '=',
            'value' => 'bar',
            'boolean' => 'AND'
        ], $this->builder->conditions[0]);
    }

    public function testSqlCompiler(): void
    {
        $this->builder
            ->select('f.id', 'f.name')
            ->from('foo', 'f')
            ->where('f.name', '=', 'foo');

        $this->assertEquals('SELECT f.id, f.name FROM foo AS f WHERE f.name = foo', $this->builder->toSQL());
    }
}
