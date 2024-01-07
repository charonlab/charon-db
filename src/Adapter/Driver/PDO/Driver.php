<?php

/*
 * This file is part of the abyss/database.
 *
 * Copyright (C) 2023-2024 Abyss Development Team
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */

namespace Abyss\Db\Adapter\Driver\PDO;

use Abyss\Db\Adapter\Driver\Driver as DriverInterface;
use Abyss\Db\Adapter\Driver\Connection as ConnectionInterface;

final readonly class Driver implements DriverInterface
{
    /**
     * {@inheritDoc}
     */
    public function connect(#[\SensitiveParameter] array $params): ConnectionInterface
    {
        $options = $params['options'] ?? [];

        if (!isset($options['persistent'])) {
            $options[\PDO::ATTR_PERSISTENT] = true;
        }

        try {
            $pdo = new \PDO(
                dsn: $params['dsn'],
                username: $params['username'] ?? null,
                password:$params['password'] ?? null
            );
        } catch (\PDOException $exc) {
            throw $exc;
        }

        return new Connection(
            $pdo
        );
    }
}
