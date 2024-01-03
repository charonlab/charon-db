<?php

/*
 * This file is part of the abyss/database.
 *
 * Copyright (C) 2023-2024 Dominik Szamburski
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE.md file for details.
 */

namespace Abyss\DBAL\Database;

use Abyss\DBAL\Statement;
use PDO;
use PDOStatement;

/**
 * Wraps PDO connection and provides common databases API.
 *
 * @author Dominik Szamburski
 * @package Abyss\DBAL\Database
 * @license LGPL-2.1
 * @since 0.5.0
 */
interface GenericDriverInterface
{
    /**
     * Attempts to create a connection with the database.
     *
     * @return PDO
     */
    public function connect(): PDO;

    /**
     * Prepares an SQL statement.
     *
     * @param string $query
     * @return PDOStatement
     */
    public function prepare(string $query): PDOStatement;

    /**
     * Executes an SQL query.
     *
     * @param string $query
     * @return PDOStatement
     */
    public function query(string $query): PDOStatement;

    /**
     * Start SQL transaction with specified isolation level.
     *
     * @param string|null $isolationLevel
     * @return bool True of success.
     */
    public function beginTransaction(string $isolationLevel = null): bool;

    /**
     * Commit the active transaction.
     *
     * @return bool True of success.
     */
    public function commit(): bool;

    /**
     * Rollback the active transaction.
     *
     * @return bool True of success.
     */
    public function rollback(): bool;

    /**
     * Returns a database platform.
     *
     * @return AbstractPlatform
     */
    public function getDatabasePlatform(): AbstractPlatform;
}
