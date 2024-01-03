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

/**
 * @author Dominik Szamburski
 * @package Abyss\DBAL
 * @license LGPL-2.1
 * @version 0.5.0
 */
final class Statement
{
    public function __construct(
        private readonly \PDOStatement $statement
    ) {
    }

    /**
     * Binds a value to parameter.
     *
     * @param int|string $param
     * @param mixed $value
     * @param ParameterType $type
     *
     * @return void
     */
    public function bindValue(int|string $param, mixed $value, ParameterType $type = ParameterType::STRING): void
    {
        $pdoType = match ($type) {
            ParameterType::NULL => \PDO::PARAM_NULL,
            ParameterType::INTEGER => \PDO::PARAM_INT,
            ParameterType::STRING => \PDO::PARAM_STR,
            ParameterType::BINARY,
            ParameterType::LARGE_OBJECT => \PDO::PARAM_LOB,
            ParameterType::BOOLEAN => \PDO::PARAM_BOOL
        };

        $this->statement->bindValue($param, $value, $pdoType);
    }

    /**
     * Executes a statement then return Result instance.
     *
     * @return Result
     */
    public function execute(): Result
    {
        $this->statement->execute();

        return new Result(
            $this->statement
        );
    }
}
