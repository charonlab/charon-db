<?php

/*
 * This file is part of the abyss/database.
 *
 * Copyright (C) 2023-2024 Abyss Development Team
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */

namespace Abyss\Db\Adapter\Driver;

interface Connection
{
    /**
     * Prepares an SQL statement.
     *
     * @param string $sql
     *  The SQL string to be preparing.
     *
     * @return \Abyss\Db\Adapter\Driver\Statement
     *  Returns a prepared statement.
     */
    public function prepare(string $sql): Statement;

    /**
     * Executes an SQL query.
     *
     * @param string $sql
     *  The SQL string to be executing.
     *
     * @return \Abyss\Db\Adapter\Driver\Result
     */
    public function query(string $sql): Result;

    /**
     * Executes an SQL statement and return the number of affected rows.
     *
     * @param string $sql
     *  The SQL string to be executing.
     *
     * @return int|numeric-string
     */
    public function execute(string $sql): int|string;

    /**
     * Starts SQL transaction with specified isolation level.
     *
     * @return bool
     *  Returns true if success, otherwise false.
     */
    public function beginTransaction(): bool;

    /**
     * Commits the active transaction.
     *
     * @return bool
     *  Returns true if success, otherwise false.
     */
    public function commit(): bool;

    /**
     * Rollbacks the active transaction.
     *
     * @return bool
     *  Returns true if success, otherwise false.
     */
    public function rollback(): bool;
}
