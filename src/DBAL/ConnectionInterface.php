<?php

/*
 * This file is part of the abyss/database.
 *
 * Copyright (C) 2023-2024 Dominik Szamburski
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE.md file for details.
 */

namespace Abyss\DBAL;

use Closure;
use Abyss\DBAL\Database\GenericDriverInterface;
use Abyss\DBAL\Query\QueryBuilderInterface;

/**
 * Connection is High Level of abstraction used to represent single Database.
 *
 * @author Dominik Szamburski
 * @package Abyss\DBAL
 * @license LGPL-2.1
 * @since 0.5.0
 */
interface ConnectionInterface
{
    /**
     * Prepares an SQL statement.
     *
     * @param string $query
     *
     * @return Statement
     */
    public function prepare(string $query): Statement;

    /**
     * Executes an, optionally parameterized, SQL query.
     *
     * @param string                            $query
     * @param list<mixed>|array<string, mixed>  $parameters
     *
     * @return Result
     */
    public function query(string $query, array $parameters = []): Result;

    /**
     * Execute a Closure within a transaction.
     *
     * @param \Closure $closure
     * @return mixed
     *
     * @throws \Throwable
     * @since 0.5.0
     */
    public function transaction(Closure $closure): mixed;

    /**
     * Starts database transaction.
     *
     * @param string|null $isolationLevel
     * @return bool
     * @since 0.5.0
     */
    public function beginTransaction(string $isolationLevel = null): bool;

    /**
     * Commit the active transaction.
     *
     * @return bool
     * @since 0.5.0
     */
    public function commit(): bool;

    /**
     * Rollback the active transaction.
     *
     * @return bool
     * @since 0.5.0
     */
    public function rollback(): bool;

    /**
     * Gets a new QueryBuilder instance.
     *
     * @return \Abyss\DBAL\Query\QueryBuilderInterface
     */
    public function getQueryBuilder(): QueryBuilderInterface;

    /**
     * Gets a driver instance.
     *
     * @return GenericDriverInterface
     */
    public function getDriver(): GenericDriverInterface;
}
