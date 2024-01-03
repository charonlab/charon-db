<?php

/*
 * This file is part of the abyss/database.
 *
 * Copyright (C) 2023-2024 Dominik Szamburski
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE.md file for details.
 */

namespace Abyss\DBAL\Database\SQLite;

use Abyss\DBAL\Query\Grammars\GrammarInterface;
use Abyss\DBAL\Query\Grammars\SQLiteGrammar;
use Abyss\DBAL\Database\AbstractPlatform;
use Abyss\DBAL\Database\GenericDriver;

/**
 * Provides a Low Level abstraction at top of SQLite.
 *
 * @author Dominik Szamburski
 * @package Abyss\DBAL\Database\SQLite
 * @license LGPL-2.1
 * @version 0.5.0
 */
final class SQLiteDriver extends GenericDriver
{
    /**
     * @inheritDoc
     */
    public function getDatabasePlatform(): AbstractPlatform
    {
        return new SQLitePlatform();
    }
}
