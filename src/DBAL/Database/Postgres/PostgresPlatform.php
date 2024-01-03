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
use Abyss\DBAL\Query\Grammars\PostgresGrammar;
use Abyss\DBAL\Database\AbstractPlatform;

/**
 * The PostgresPlatform class describes the specifics and dialects of the Postgres database platform.
 *
 * @author Dominik Szamburski
 * @package Abyss\DBAL\Database\Postgres
 * @license LGPL-2.1
 * @version 0.5.0
 */
final class PostgresPlatform extends AbstractPlatform
{
    /**
     * @inheritDoc
     */
    public function getGrammar(): GrammarInterface
    {
        return new PostgresGrammar();
    }
}
