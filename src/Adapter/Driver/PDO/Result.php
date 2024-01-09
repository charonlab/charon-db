<?php

/*
 * This file is part of the abyss/abyss-db.
 *
 * Copyright (C) 2023-2024 Abyss Development Team
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */

namespace Abyss\Db\Adapter\Driver\PDO;

use Abyss\Db\Adapter\Driver\Result as ResultInterface;

final class Result implements ResultInterface
{
    private int $fetchMode = \PDO::FETCH_OBJ;

    public function __construct(
        private readonly \PDOStatement $resource
    ) {
    }

    public function setFetchMode(int $fetchMode): void
    {
        $this->fetchMode = $fetchMode;
    }

    /**
     * {@inheritDoc}
     */
    public function fetch(): mixed
    {
        return $this->resource->fetch($this->fetchMode);
    }

    /**
     * {@inheritDoc}
     */
    public function fetchAll(): array
    {
        return $this->resource->fetchAll($this->fetchMode);
    }

    /**
     * {@inheritDoc}
     */
    public function rowCount(): int
    {
        return $this->resource->rowCount();
    }

    /**
     * {@inheritDoc}
     */
    public function columnCount(): int
    {
        return $this->resource->columnCount();
    }
}
