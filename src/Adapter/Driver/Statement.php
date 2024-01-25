<?php

/*
 * This file is part of the charonlab/charon-db.
 *
 * Copyright (C) 2023-2024 Charon Lab Development Team
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */

namespace Charon\Db\Adapter\Driver;

use Charon\Db\Sql\ParameterType;

interface Statement
{
    /**
     * Binds a value to parameter.
     *
     * @param int|string $param
     * @param mixed $value
     * @param \Charon\Db\Sql\ParameterType $type
     *
     * @return void
     */
    public function bindValue(
        int|string $param,
        mixed $value,
        ParameterType $type = ParameterType::STRING
    ): void;

    /**
     * @return \Charon\Db\Adapter\Driver\Result
     *  Returns result sets.
     */
    public function execute(): Result;
}
