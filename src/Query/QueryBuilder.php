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

use Charon\Db\Adapter\ResultSetInterface;
use Charon\Db\Connection;
use Charon\Db\Query\Clause\Column;
use Charon\Db\Query\Clause\Condition;
use Charon\Db\Query\Clause\Expression;
use Charon\Db\Query\Clause\Group;
use Charon\Db\Query\Clause\Join;
use Charon\Db\Query\Clause\Order;
use Charon\Db\Query\Clause\From;
use Charon\Db\Query\Clause\Set;

class QueryBuilder implements QueryBuilderInterface
{
    private QueryType $queryType = QueryType::SELECT;

    /** @var bool $distinct */
    private bool $distinct = false;

    /** @var int|null $limit */
    private ?int $limit = null;

    /** @var int|null $offset */
    private ?int $offset = null;

    /** @var \Charon\Db\Query\Clause\Column[] $columns */
    private array $columns = [];

    /** @var null|\Charon\Db\Query\Clause\From $table */
    private ?From $table = null;

    /** @var \Charon\Db\Query\Clause\From[] $tables */
    private array $tables = [];

    /** @var \Charon\Db\Query\Clause\Join[][] $joins */
    private array $joins = [];

    /** @var \Charon\Db\Query\Clause\Condition[] $conditions */
    private array $conditions = [];

    /** @var \Charon\Db\Query\Clause\Group[] $groups */
    private array $groups = [];
    /** @var \Charon\Db\Query\Clause\Expression[] $having */
    private array $having = [];

    /** @var \Charon\Db\Query\Clause\Order[] $orders */
    private array $orders = [];

    /** @var array<string, scalar> $values */
    private array $values = [];

    /** @var \Charon\Db\Query\Clause\Set[] $sets */
    private array $sets = [];


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
    public function insert(string $table): self {
        $this->queryType = QueryType::INSERT;
        $this->table = new From($table);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function delete(string $table): self {
        $this->queryType = QueryType::DELETE;
        $this->table = new From($table);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function update(string $table): self {
        $this->queryType = QueryType::UPDATE;
        $this->table = new From($table);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function distinct(bool $distinct = true): self {
        $this->distinct = $distinct;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function limit(int $limit): self {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function offset(int $offset): self {
        $this->offset = $offset;
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
    public function orWhere(
        string $column,
        string|float|int|array $value,
        string $operator = '=',
    ): self {
        $this->conditions[] = new Condition($column, $value, $operator, 'OR');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function andWhere(
        string $column,
        string|float|int|array $value,
        string $operator = '=',
    ): self {
        $this->conditions[] = new Condition($column, $value, $operator, 'AND');
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
    public function having(string $expression): self {
        $this->having = [];
        $this->having[] = new Expression('AND', $expression);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function orHaving(string $expression): self {
        $this->having[] = new Expression('OR', $expression);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function andHaving(string $expression): self {
        $this->having[] = new Expression('AND', $expression);
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
    public function values(array $values): self {
        $this->values = $values;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function set(string $column, string $value): self {
        $this->sets[] = new Set($column, $value);
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
            QueryType::SELECT => $this->compileSelect(),
            QueryType::INSERT => $this->compileInsert(),
            QueryType::DELETE => $this->compileDelete(),
            QueryType::UPDATE => $this->compileUpdate(),
        };
    }

    /**
     * @inheritDoc
     */
    public function execute(array $parameters = []): ResultSetInterface {
        return $this->connection->query($this->compile(), $parameters);
    }

    /**
     * Compiles the SELECT Statement.
     *
     * @return string
     */
    private function compileSelect(): string {
        $parts = ['SELECT'];

        if ($this->distinct) {
            $parts[] = 'DISTINCT';
        }

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
            $parts[] = 'WHERE' . $this->removeLogicalOperatorFromBeginning($this->conditions);
        }

        if (\count($this->groups) > 0) {
            $parts[] = 'GROUP BY ' . \implode(', ', $this->groups);
        }

        if (\count($this->having) > 0) {
            $parts[] = 'HAVING' . $this->removeLogicalOperatorFromBeginning($this->having);
        }

        if (\count($this->orders) > 0) {
            $parts[] = 'ORDER BY ' . \implode(', ', $this->orders);
        }

        if ($this->limit !== null) {
            $parts[] = 'LIMIT ' . $this->limit;
        }

        if ($this->offset !== null) {
            $parts[] = 'OFFSET ' . $this->offset;
        }

        return \implode(' ', $parts);
    }

    /**
     * Compiles the INSERT Statement.
     *
     * @return string
     * @throws \LogicException
     */
    private function compileInsert(): string {
        if ($this->table === null) {
            throw new \LogicException('No table specified for INSERT statement');
        }

        return \sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $this->table,
            \implode(', ', \array_keys($this->values)),
            \implode(', ', $this->values)
        );
    }

    /**
     * Compiles the DELETE Statement.
     *
     * @return string
     * @throws \LogicException
     */
    private function compileDelete(): string {
        if ($this->table === null) {
            throw new \LogicException('No table specified for INSERT statement');
        }

        $parts = ['DELETE FROM'];
        $parts[] = $this->table;

        if (\count($this->conditions) > 0) {
            $parts[] = 'WHERE' . $this->removeLogicalOperatorFromBeginning($this->conditions);
        }

        if ($this->limit !== null) {
            if (\count($this->orders) > 0) {
                $parts[] = 'ORDER BY ' . \implode(', ', $this->orders);
            }

            $parts[] = 'LIMIT ' . $this->limit;
        }

        return \implode(' ', $parts);
    }

    /**
     * Compiles the UPDATE Statement.
     *
     * @return string
     * @throws \LogicException
     */
    private function compileUpdate(): string {
        if ($this->table === null) {
            throw new \LogicException('No table specified for INSERT statement');
        }

        $parts = ['UPDATE'];
        $parts[] = $this->table;

        if (\count($this->sets) > 0) {
            $parts[] = 'SET ' . \implode(', ', $this->sets);
        }

        if (\count($this->conditions) > 0) {
            $parts[] = 'WHERE' . $this->removeLogicalOperatorFromBeginning($this->conditions);
        }

        return \implode(' ', $parts);
    }

    /**
     * Removes a logical operator from beginning.
     *
     * @param Condition[]|Expression[] $parts
     *
     * @return string
     */
    private function removeLogicalOperatorFromBeginning(array $parts): string {
        return (string) \preg_replace('/AND|OR/i', '', \implode(' ', $parts), 1);
    }
}
