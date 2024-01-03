<?php

/*
 * This file is part of the abyss/database.
 *
 * Copyright (C) 2023-2024 Dominik Szamburski
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE.md file for details.
 */

namespace Abyss\DBAL\Database;

use Abyss\DBAL\Query\Grammars\GrammarInterface;

/**
 * @author Dominik Szamburski
 * @package Abyss\DBAL\Database
 * @license LGPL-2.1
 * @version 0.5.0
 */
abstract class AbstractPlatform
{
    /**
     * Gets a grammar for specify database platform.
     *
     * @return \Abyss\DBAL\Query\Grammars\GrammarInterface
     */
    abstract public function getGrammar(): GrammarInterface;
}
