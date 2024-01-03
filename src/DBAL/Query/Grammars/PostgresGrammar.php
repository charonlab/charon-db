<?php

/*
 * This file is part of the abyss/database.
 *
 * Copyright (C) 2023-2024 Dominik Szamburski
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE.md file for details.
 */

namespace Abyss\DBAL\Query\Grammars;

use Abyss\DBAL\Query\QueryBuilder;

/**
 * @internal
 *
 * @author Damian Mosiński
 * @package Abyss\DBAL\Builder\Grammars
 * @license LGPL-2.1
 * @since 0.5.0
 */
class PostgresGrammar extends Grammar implements GrammarInterface
{
    /**
     * @inheritDoc
     */
    public function compileLimit(QueryBuilder $query): string
    {
        return ($query->limit == 0 ? '' : 'LIMIT ' . $query->limit)
        . ($query->offset === null ? '' : 'OFFSET ' . $query->offset);
    }
}
