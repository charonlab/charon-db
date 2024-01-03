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

use Abyss\DBAL\Database\MySQL\MySQLDriver;
use Abyss\DBAL\Database\Postgres\PostgresDriver;
use Abyss\DBAL\Database\SQLite\SQLiteDriver;
use Abyss\DBAL\Exception\UnsupportedDriverException;

/**
 * @author Dominik Szamburski
 * @package Abyss\DBAL\Database
 * @license LGPL-2.1
 * @version 0.5.0
 *
 * @phpstan-import-type ConnectionParams from \Abyss\DBAL\ConnectionManager
 *
 */
final class GenericDriverFactory
{
    /**
     * @param ConnectionParams $params
     * @return GenericDriverInterface
     *
     * @throws UnsupportedDriverException
     */
    public function createDriver(#[\SensitiveParameter] array $params): GenericDriverInterface
    {
        return match ($params['driver']) {
            'mysql' => new MySQLDriver($params),
            'pqsql' => new PostgresDriver($params),
            'sqlite' => new SQLiteDriver($params),
            default => throw new UnsupportedDriverException("The given driver '{$params['driver']}' is unknown.")
        };
    }
}
