<?php

/*
 * This file is part of the abyss/database.
 *
 * Copyright (C) 2023-2024 Dominik Szamburski
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE.md file for details.
 */

namespace Abyss\DBAL\Database\Postgres;

use Abyss\DBAL\Query\Grammars\GrammarInterface;
use Abyss\DBAL\Database\AbstractPlatform;
use Abyss\DBAL\Database\GenericDriver;

/**
 * Provides a Low Level abstraction at top of PostgreSQL.
 *
 * @author Dominik Szamburski
 * @package Abyss\DBAL\Database\Posgres
 * @license LGPL-2.1
 * @version 0.5.0
 */
final class PostgresDriver extends GenericDriver
{
    /**
     * @inheritDoc
     */
    public function getDatabasePlatform(): AbstractPlatform
    {
        return new PostgresPlatform();
    }
}
