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
use Abyss\DBAL\FetchMode;
use Abyss\Stdlib\Collections\CollectionInterface;

/**
 * SQL Query Builder with fluid interface SQL.
 *
 * @author Dominik Szamburski
 * @package Abyss\DBAL\Query
 * @license LGPL-2.1
 * @since 0.3.0
 */
interface QueryBuilderInterface
{
    /**
     * Set the columns to be selected.
     *
     * @param string ...$columns
     * @return self
     */
    public function select(string ...$columns): self;

    /**
     * Turns the query being built into a INSERT query.
     *
     * @param string $table
     * @return self
     *
     * @since 0.6.0
     */
    public function insert(string $table): self;

    /**
     * Turns the query being built into a UPDATE query.
     *
     * @param string $table
     * @return self
     *
     * @since 0.6.0
     */
    public function update(string $table): self;

    /**
     * Turns the query being built into a DELETE query.
     *
     * @param string $table
     * @return self
     *
     * @since 0.6.0
     */
    public function delete(string $table): self;

    /**
     *
     *
     * @param array<string, mixed> $values
     * @return self
     *
     * @since 0.6.0
     */
    public function values(array $values): self;

    /**
     * Set the table which the query is targeting.
     *
     * @param string $table
     * @param string|null $as
     * @return self
     */
    public function from(string $table, string $as = null): self;

    /**
     * Add where clause to the query.
     *
     * @param string $column
     * @param string $operator
     * @param mixed $values
     * @param string $boolean
     * @return self
     *
     * @throws InvalidArgumentException
     */
    public function where(
        string $column,
        string $operator,
        mixed $values,
        string $boolean = 'AND'
    ): self;

    /**
     * Adds ORDER BY clause to the query.
     *
     * @param string $sort
     * @param string|null $order
     *
     * @return self
     */
    public function orderBy(string $sort, ?string $order = null): self;

    /**
     * Adds LIMIT clause to the query.
     *
     * @param int $limit
     * @param int|null $offset
     *
     * @return self
     */
    public function limit(int $limit, ?int $offset = null): self;

    /**
     * Get the SQL representation.
     *
     * @return string
     */
    public function toSQL(): string;

    /**
     * Execute the "SELECT" Query.
     *
     * @param \Abyss\DBAL\FetchMode $fetchMode
     * @return CollectionInterface
     *
     * @deprecated 0.7.0
     */
    public function get(FetchMode $fetchMode = FetchMode::OBJECT): CollectionInterface;
}
