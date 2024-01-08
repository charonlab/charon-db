<?php

namespace Abyss\Test\Performance;

use Abyss\Db\Connection;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('performance')]
class MemoryUsageTest extends TestCase
{
    #[DoesNotPerformAssertions]
    public function testMemoryUsage()
    {
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