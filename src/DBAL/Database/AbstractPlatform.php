<?php

/**
 * Copyright (C) 2023 Dominik Szamburski
 *
 * This file is part of abyss/dbal
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
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
