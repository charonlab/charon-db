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

/**
 * The SQLitePlatform class describes the specifics and dialects of the SQLite database platform.
 *
 * @author Dominik Szamburski
 * @package Abyss\DBAL\Database\SQLite
 * @license LGPL-2.1
 * @version 0.5.0
 */
final class SQLitePlatform extends AbstractPlatform
{
    /**
     * @inheritDoc
     */
    public function getGrammar(): GrammarInterface
    {
        return new SQLiteGrammar();
    }
}
