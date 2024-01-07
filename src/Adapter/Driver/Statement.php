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

use Abyss\Db\Sql\ParameterType;

interface Statement
{
    /**
     * Binds a value to parameter.
     *
     * @param int|string $param
     * @param mixed $value
     * @param \Abyss\Db\Sql\ParameterType $type
     *
     * @return void
     */
    public function bindValue(
        int|string $param,
        mixed $value,
        ParameterType $type = ParameterType::STRING
    ): void;

    /**
     * @return \Abyss\Db\Adapter\Driver\Result
     *  Returns result sets.
     */
    public function execute(): Result;
}
