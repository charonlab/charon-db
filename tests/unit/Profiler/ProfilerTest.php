<?php

/*
 * This file is part of the charonlab/charon-db.
 *
 * Copyright (C) 2023-2024 Charon Lab Development Team
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */

namespace Charon\Test\Unit\Profiler;

use Charon\Db\Adapter\Profiler\Profiler;
use Charon\Db\Exception\RuntimeException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Profiler::class)]
class ProfilerTest extends TestCase
{
    public function testCallStopBeforeStartThrowsException(): void {
        self::expectException(RuntimeException::class);
        self::expectExceptionMessage('A profiler must be started before stop can be called');

        $profiler = new Profiler();
        $profiler->stop();
    }
}
