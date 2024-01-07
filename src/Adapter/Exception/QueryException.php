<?php

/*
 * This file is part of the abyss/database.
 *
 * Copyright (C) 2023-2024 Abyss Development Team
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */

namespace Abyss\Db\Adapter\Exception;

class QueryException extends \RuntimeException
{
    protected string $sql;
    protected array $bindings;

    public function __construct(string $sql, array $bindings, \Throwable $previous)
    {
        parent::__construct('', 0, $previous);

        $this->sql = $sql;
        $this->bindings = $bindings;
        $this->code = (int) $previous->getCode();
        $this->message = $this->formatMessage($sql, $bindings);
    }

    protected function formatMessage(string $sql, array $bindings): string
    {
        return \sprintf(
            "SQL: %s\nBindings: %s",
            $sql,
            \var_export($bindings, true)
        );
    }
}
