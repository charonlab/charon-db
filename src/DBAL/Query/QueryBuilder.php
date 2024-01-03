<?php

/*
 * This file is part of the abyss/database.
 *
 * Copyright (C) 2023-2024 Dominik Szamburski
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE.md file for details.
 */

namespace Abyss\DBAL\Query;

use InvalidArgumentException;
use Abyss\DBAL\ConnectionInterface;
use Abyss\DBAL\FetchMode;
use Abyss\DBAL\Query\Grammars\Grammar;
use Abyss\DBAL\Query\Grammars\GrammarInterface;
use Nulldark\Stdlib\Collections\CollectionInterface;

/**
 * @author Dominik Szamburski
 * @package Abyss\DBAL\Builder
 * @license LGPL-2.1
 * @version 0.3.0
 */
class QueryBuilder implements QueryBuilderInterface
{
    /** @var GrammarInterface $grammar */
    public GrammarInterface $grammar;

    /** @var array|string[] $operators */
    public array $operators = [
        '=', '<', '>', '<=', '>=', '<>', '!=', '<=>',
    ];

    /** @var string[] $columns */
    public array $columns;

    /** @var string[] $from */
    public array $from = [];

    /** @var array<array-key, mixed[]> $conditions */
    public array $conditions = [];

    /** @var string[] $orders */
    public array $orders = [];

    /** @var int $limit */
    public int $limit = 0;

    /** @var ?int $offset */
    public ?int $offset = null;

    /** @var array<array-key, array<string, mixed>> $values  */
    public array $values = [];

    /** @var string $table */
    public string $table;

    /** @var ConnectionInterface $connection */
    private ConnectionInterface $connection;

    public QueryType $type = QueryType::SELECT;

    public function __construct(
        ConnectionInterface $connection,
        Grammar $grammar = null
    ) {
        $this->connection = $connection;
        $this->grammar = $grammar ?: $connection->getDriver()
            ->getDatabasePlatform()
            ->getGrammar();
    }

    /**
     * @inheritDoc
     */
    public function select(string ...$columns): self
    {
        $this->type = QueryType::SELECT;

        $this->columns = [];

        if (empty($columns)) {
            $columns = ['*'];
        }

        foreach ($columns as $column) {
            $this->columns[] = $column;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function insert(string $table): QueryBuilderInterface
    {
        $this->type = QueryType::INSERT;
        $this->table = $table;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function update(string $table): QueryBuilderInterface
    {
        $this->type = QueryType::UPDATE;
        $this->table = $table;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function delete(string $table): QueryBuilderInterface
    {
        $this->type = QueryType::DELETE;
        $this->table = $table;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function from(string $table, string $as = null): self
    {
        $this->from[] = $as === null ? $table : "$table AS $as";
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function where(string $column, string $operator, mixed $values, string $boolean = 'AND'): self
    {
        [$value, $operator] = $this->prepareValueAndOperator(
            $values,
            $operator,
            func_num_args() === 2
        );

        $this->conditions[] = compact(
            'column',
            'operator',
            'value',
            'boolean'
        );

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toSQL(): string
    {
        return $this->grammar->compile($this);
    }

    /**
     * @inheritDoc
     */
    public function get(FetchMode $fetchMode = FetchMode::OBJECT): CollectionInterface
    {
        $result = $this->connection->query(
            $this->toSQL()
        );

        return match ($fetchMode) {
            FetchMode::ASSOC => $result->fetchAllAssociative(),
            FetchMode::NUMERIC => $result->fetchAllNumeric(),
            default => $result->fetchAllObject()
        };
    }

    /**
     * Checks if a valid operator has been passed and returns the checked value with the operator.
     *
     * @param mixed $value
     * @param string $operator
     * @param bool $useDefault
     *
     * @return mixed[]
     *
     * @throws InvalidArgumentException if is illegal operator combination.
     */
    private function prepareValueAndOperator(
        mixed $value,
        string $operator,
        bool $useDefault = false
    ): array {
        if ($useDefault) {
            return [$operator, '='];
        } elseif (in_array($operator, $this->operators) && !in_array($operator, ['=', '<>', '!='])) {
            throw new InvalidArgumentException('Illegal operator and value combination.');
        }

        return [$value, $operator];
    }

    /**
     * @inheritDoc
     */
    public function orderBy(string $sort, ?string $order = null): QueryBuilderInterface
    {
        $this->orders[] = "$sort" . ($order === null ? '' : ' ' . $order);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function limit(int $limit, ?int $offset = null): QueryBuilderInterface
    {
        $this->limit = $limit;
        $this->offset = $offset === null ? null : $offset;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function values(array $values): QueryBuilderInterface
    {
        $this->values[] = $values;
        return $this;
    }
}
