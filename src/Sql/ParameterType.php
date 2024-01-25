<?php

/*
 * This file is part of the charonlab/charon-db.
 *
 * Copyright (C) 2023-2024 Charon Lab Development Team
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */

namespace Charon\Db\Sql;

enum ParameterType
{
    case NULL;
    case STRING;
    case INTEGER;
    case BINARY;
    case LARGE_OBJECT;
    case BOOLEAN;

    public static function getType(mixed $value): self {
        return match (true) {
            \is_int($value) => ParameterType::INTEGER,
            \is_resource($value) => ParameterType::LARGE_OBJECT,
            \is_bool($value) => ParameterType::BOOLEAN,
            default => ParameterType::STRING
        };
    }
}
