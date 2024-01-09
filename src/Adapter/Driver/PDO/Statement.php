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
use Abyss\Db\Adapter\Driver\Statement as StatementInterface;
use Abyss\Db\Sql\ParameterType;

final readonly class Statement implements StatementInterface
{
    public function __construct(
        private \PDOStatement $resource
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function execute(): ResultInterface
    {
        $this->resource->execute();

        return new Result(
            $this->resource
        );
    }

    /**
     * {@inheritDoc}
     */
    public function bindValue(
        int|string $param,
        mixed $value,
        ParameterType $type = ParameterType::STRING
    ): void {
        $pdoType = match ($type) {
            ParameterType::NULL => \PDO::PARAM_NULL,
            ParameterType::INTEGER => \PDO::PARAM_INT,
            ParameterType::STRING => \PDO::PARAM_STR,
            ParameterType::BINARY,
            ParameterType::LARGE_OBJECT => \PDO::PARAM_LOB,
            ParameterType::BOOLEAN => \PDO::PARAM_BOOL
        };

        $this->resource->bindValue($param, $value, $pdoType);
    }
}
