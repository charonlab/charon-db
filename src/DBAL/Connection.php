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
use Abyss\DBAL\Query\QueryBuilder;
use Abyss\DBAL\Database\GenericDriverInterface;

/**
 * Connection class is a High Level of abstraction at top of Driver.
 *
 * @author Dominik Szamburski
 * @package Abyss\DBAL
 * @license LGPL-2.1
 * @version 0.3.0
 */
class Connection implements ConnectionInterface
{
    public function __construct(
        protected string $name,
        protected GenericDriverInterface $driver,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function prepare(string $query): Statement
    {
        return new Statement(
            $this->driver->prepare($query)
        );
    }

    /**
     * @inheritDoc
     */
    public function query(string $query, array $parameters = []): Result
    {
        $stmt = $this->driver->query($query);
        $stmt->execute();

        return new Result(
            $stmt
        );
    }

    /**
     * @inheritDoc
     */
    public function getQueryBuilder(): QueryBuilder
    {
        return new QueryBuilder($this);
    }

    /**
     * @inheritDoc
     */
    public function beginTransaction(string $isolationLevel = null): bool
    {
        return $this->driver->beginTransaction($isolationLevel);
    }

    /**
     * @inheritDoc
     */
    public function commit(): bool
    {
        return $this->driver->commit();
    }

    /**
     * @inheritDoc
     */
    public function rollback(): bool
    {
        return $this->driver->rollback();
    }

    /**
     * @inheritDoc
     */
    public function transaction(Closure $closure): mixed
    {
        $this->beginTransaction();

        try {
            $result = $closure($this);
            $this->commit();

            return $result;
        } catch (\Throwable $e) {
            $this->rollback();
            throw $e;
        }
    }

    /**
     * @inheritDoc
     */
    public function getDriver(): GenericDriverInterface
    {
        return $this->driver;
    }
}
