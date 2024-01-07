<?php

/*
 * This file is part of the abyss/database.
 *
 * Copyright (C) 2023-2024 Abyss Development Team
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */

namespace Abyss\Db\Adapter\Profiler;

/**
 * @psalm-type QueryLog array{
 *     sql: string,
 *     bindings: array,
 *     start: float,
 *     end: float,
 *     elapse: float
 * }
 */
interface ProfilerInterface
{
    /**
     * Starts profiling current SQL.
     *
     * @param string $sql
     *  The SQL string.
     * @param array $bindings
     *  The SQL parameters.
     *
     * @return self
     */
    public function start(string $sql, array $bindings): self;

    /**
     * Stops profiling a current SQL.
     *
     * @return self
     *  Provides a fluent interface.
     *
     * @throws \RuntimeException
     */
    public function stop(): self;

    /**
     * Gets all query logs.
     *
     * @return QueryLog[]
     */
    public function getQueryLogs(): array;
}
