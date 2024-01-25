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
 * @psalm-import-type QueryLog from \Charon\Db\Adapter\Profiler\ProfilerInterface
 */
class Profiler implements ProfilerInterface
{
    protected int $currentIndex = 0;

    /**
     * @var QueryLog[] $queryLogs
     */
    protected array $queryLogs = [];

    /**
     * {@inheritDoc}
     */
    public function start(string $sql, array $bindings): self {
        $queryLog = [
            'sql' => $sql,
            'bindings' => $bindings,
            'start' => \microtime(true),
            'end' => 0,
            'elapse' => 0,
        ];

        $this->queryLogs[$this->currentIndex] = $queryLog;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function stop(): self {
        if (!isset($this->queryLogs[$this->currentIndex])) {
            $reason = \sprintf(
                'A profiler must be started before %s can be called',
                __FUNCTION__
            );

            throw new \RuntimeException($reason);
        }

        $current = $this->queryLogs[$this->currentIndex];

        $current['end'] = \microtime(true);
        $current['elapse'] = $current['end'] - $current['start'];

        $this->queryLogs[$this->currentIndex] = $current;

        $this->currentIndex++;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getQueryLogs(): array {
        return $this->queryLogs;
    }
}
