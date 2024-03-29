<?php

/*
 * This file is part of the charonlab/charon-db.
 *
 * Copyright (C) 2023-2024 Charon Lab Development Team
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */

namespace Charon\Db\Adapter\Profiler;

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
     * @throws \Charon\Db\Exception\RuntimeException
     */
    public function stop(): self;

    /**
     * Gets all query logs.
     *
     * @return QueryLog[]
     */
    public function getQueryLogs(): array;
}
