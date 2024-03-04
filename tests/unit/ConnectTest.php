<?php

/*
 * This file is part of the charonlab/charon-db.
 *
 * Copyright (C) 2023-2024 Charon Lab Development Team
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */

namespace Charon\Test\Unit;

use Charon\Db\Adapter\Exception\MissingDriverException;
use Charon\Db\Adapter\Exception\UnsupportedDriverException;
use Charon\Db\Connection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Connection::class)]
class ConnectTest extends TestCase
{
    public function testMissingDriverOptionThrowsException(): void {
        self::expectException(MissingDriverException::class);
        /** @psalm-suppress InvalidArgument */
        new Connection([]);
    }

    public function testUnsupportedDriverThrowException(): void {
        self::expectException(UnsupportedDriverException::class);
        /** @psalm-suppress InvalidArgument */
        new Connection(['driver' => 'foobar']);
    }
}
