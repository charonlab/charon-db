<?php

/*
 * This file is part of the charonlab/charon-db.
 *
 * Copyright (C) 2023-2024 Charon Lab Development Team
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */

namespace Charon\Test\Performance;

use Charon\Db\Connection;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('performance')]
class InsertPerformanceTest extends TestCase
{
    protected Connection $connection;

    public function setUp(): void
    {
        $this->connection = new Connection([
            'driver' => 'pdo',
            'dsn' => 'sqlite::memory:'
        ]);

        $this->connection->execute('CREATE TABLE charon(name TEXT);');
    }

    public function tearDown(): void
    {
        unset(
            $this->connection
        );
    }

    #[DoesNotPerformAssertions]
    public function testInsertPerformance(): void
    {
        $start = \microtime(true);

        echo 'Memory usage before: ' . (\memory_get_usage() / 1024) . ' KB' . PHP_EOL;

        for ($i = 1; $i <= 20000; ++$i) {
            $this->connection->execute('INSERT INTO charon VALUES("John Doe");');
        }

        \gc_collect_cycles();

        echo 'Memory usage after: ' . (\memory_get_usage() / 1024) . ' KB' . PHP_EOL;
        echo 'Inserted 20000 objects in ' . (\microtime(true) - $start) . ' seconds' . PHP_EOL;
    }
}