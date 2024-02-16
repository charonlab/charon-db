<?php

/*
 * This file is part of the charonlab/charon-db.
 *
 * Copyright (C) 2023-2024 Charon Lab Development Team
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */

namespace Charon\Db\Query;

use Charon\Db\Connection;
use Charon\Db\Query\Clause\Column;
use Charon\Db\Query\Clause\Condition;
use Charon\Db\Query\Clause\Group;
use Charon\Db\Query\Clause\Join;
use Charon\Db\Query\Clause\Order;
use Charon\Db\Query\Clause\From;

class QueryBuilder implements QueryBuilderInterface
{
    private QueryType $queryType = QueryType::SELECT;

    /** @var \Charon\Db\Query\Clause\Column[] $columns */
    private array $columns = [];

    /** @var \Charon\Db\Query\Clause\From[] $tables */
    private array $tables = [];

    /** @var \Charon\Db\Query\Clause\Join[][] $joins */
    private array $joins = [];

    /** @var \Charon\Db\Query\Clause\Condition[] $conditions */
    private array $conditions = [];

    /** @var \Charon\Db\Query\Clause\Group[] $groups */
    private array $groups = [];

    /** @var \Charon\Db\Query\Clause\Order[] $orders */
    private array $orders = [];


    public function __construct(
        protected readonly Connection $connection
    ) {
    }

    /**
     * @inheritDoc
     */
    public function select(string ...$columns): self {
        $this->queryType = QueryType::SELECT;

        $this->columns = [];

        if (empty($columns)) {
            $columns = ['*'];
        }

        foreach ($columns as $column) {
            $this->addColumn($column);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function from(string $table, string $alias = null): self {
        $this->tables[] = new From($table, $alias);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function where(
        string $column,
        string|float|int|array $value,
        string $operator = '=',
        string $boolean = 'AND'
    ): self {
        $this->conditions[] = new Condition($column, $value, $operator, $boolean);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function join(string $fromAlias, string $table, string $alias, ?string $on = null): self {
        $this->joins[$fromAlias][] = new Join('INNER', $table, $alias, $on);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function leftJoin(string $fromAlias, string $table, string $alias, ?string $on = null): self {
        $this->joins[$fromAlias][] = new Join('LEFT', $table, $alias, $on);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function rightJoin(string $fromAlias, string $table, string $alias, ?string $on = null): self {
        $this->joins[$fromAlias][] = new Join('RIGHT', $table, $alias, $on);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function groupBy(string $column): self {
        $this->groups[] = new Group($column);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orderBy(string $column, string $direction = 'ASC'): self {
        $this->orders[] = new Order($column, $direction);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addColumn(string $column, ?string $alias = null): string {
        $columnKey = $column . ($alias ?? '');

        $columnKeyCandidate = $columnKey;
        $count = 2;

        while (!empty($this->columns[$columnKeyCandidate])) {
            $columnKeyCandidate = $columnKey . '_' . $count++;
        }

        $columnKey = $columnKeyCandidate;

        $this->columns[$columnKey] = new Column($column, $alias);

        return $columnKey;
    }

    public function compile(): string {
        return match ($this->queryType) {
            QueryType::SELECT => $this->compileSelect()
        };
    }

    /**
     * Compiles the SELECT Statement.
     *
     * @return string
     */
    private function compileSelect(): string {
        $parts = ['SELECT'];
        $parts[] = \implode(', ', $this->columns);

        if (\count($this->tables) > 0) {
            $tables = \array_map(function (From $table) {
                $reference = ($table->alias === null || $table->alias === $table->table)
                    ? $table->table
                    : $table->alias;

                return $table . ' ' . \implode(' ', $this->joins[$reference] ?? []);
            }, $this->tables);

            $parts[] = \rtrim('FROM ' . \implode(', ', $tables));
        }

        if (\count($this->conditions) > 0) {
            $parts[] = 'WHERE' . \preg_replace('/AND|OR/i', '', \implode(' ', $this->conditions), 1);
        }

        if (\count($this->groups) > 0) {
            $parts[] = 'GROUP BY ' . \implode(', ', $this->groups);
        }

        if (\count($this->orders) > 0) {
            $parts[] = 'ORDER BY ' . \implode(', ', $this->orders);
        }

        return \implode(' ', $parts);
    }
}
