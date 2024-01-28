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
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('performance')]
#[CoversNothing]
class MemoryUsageTest extends TestCase
{
    #[DoesNotPerformAssertions]
    public function testMemoryUsage() {
        $connection = new Connection([
            'driver' => 'pdo',
            'dsn' => 'sqlite:memory'
        ]);

        $memoryUsage = [];
        for ($i = 0; $i < 200; $i++) {
            $connection->execute('SELECT 1+1');

            \gc_collect_cycles();
            $memoryUsage[] = \memory_get_usage();
        }

        $start = \current($memoryUsage);
        $end   = \end($memoryUsage);

        echo \sprintf('Memory increased by %s', ($end - $start) / 1024 . ' KB') . PHP_EOL;
    }
}
