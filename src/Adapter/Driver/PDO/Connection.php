<?php

/*
 * This file is part of the charonlab/charon-db.
 *
 * Copyright (C) 2023-2024 Charon Lab Development Team
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */

namespace Charon\Db\Adapter\Driver\PDO;

use Charon\Db\Adapter\Driver\Connection as ConnectionInterface;
use Charon\Db\Adapter\Driver\Result as ResultInterface;
use Charon\Db\Adapter\Driver\Statement as StatementInterface;

final readonly class Connection implements ConnectionInterface
{
    public function __construct(
        private \PDO $connection
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function query(string $sql): ResultInterface
    {
        $stmt =  $this->connection->query($sql);

        \assert($stmt instanceof \PDOStatement);

        return new Result(
            $stmt
        );
    }

    /**
     * {@inheritDoc}
     */
    public function prepare(string $sql): StatementInterface
    {
        $stmt = $this->connection->prepare($sql);

        \assert($stmt instanceof \PDOStatement);

        return new Statement(
            $stmt
        );
    }

    /**
     * {@inheritDoc}
     */
    public function beginTransaction(): bool
    {
        return $this->connection->beginTransaction();
    }

    /**
     * {@inheritDoc}
     */
    public function commit(): bool
    {
        return $this->connection->commit();
    }

    /**
     * {@inheritDoc}
     */
    public function rollback(): bool
    {
        return $this->connection->rollBack();
    }

    /**
     * {@inheritDoc}
     */
    public function execute(string $sql): int|string
    {
        $result = $this->connection->exec($sql);

        \assert($result !== false);

        return $result;
    }
}
