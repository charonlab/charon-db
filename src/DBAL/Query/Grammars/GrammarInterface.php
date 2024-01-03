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
 * @package Abyss\DBAL\Contract\Builder\Grammars
 * @license LGPL-2.1
 * @since 0.5.0
 */
interface GrammarInterface
{
    /**
     * Compiles the provided Builder.
     *
     * @param QueryBuilder $query
     * @return string
     */
    public function compile(QueryBuilder $query): string;

    /**
     * Builds a SELECT Query.
     *
     * @param QueryBuilder $query
     * @return string
     *
     * @since 0.6.0
     * */
    public function buildSelectSQL(QueryBuilder $query): string;

    /**
     * Builds a INSERT Query.
     *
     * @param QueryBuilder $query
     * @return string
     *
     *
     * @since 0.6.0
     */
    public function buildInsertSQL(QueryBuilder $query): string;

    /**
     * Builds a UPDATE Query.
     *
     * @param QueryBuilder $query
     * @return string
     *
     * @since 0.6.0
     */
    public function buildUpdateSQL(QueryBuilder $query): string;

    /**
     * Builds a DELETE Query.
     *
     * @param QueryBuilder $query
     * @return string
     *
     * @since 0.6.0
     */
    public function buildDeleteSQL(QueryBuilder $query): string;

    /**
     * Compiles SELECT clauses into SQL.
     *
     * @param QueryBuilder $query
     * @return string
     *
     * @since 0.5.0
     */
    public function compileSelects(QueryBuilder $query): string;

    /**
     * Compiles FROM clauses into SQL.
     *
     * @param QueryBuilder $query
     * @return string
     *
     * @since 0.5.0
     */
    public function compileFroms(QueryBuilder $query): string;

    /**
     * Compiles conditions into SQL.
     *
     * @param QueryBuilder $query
     * @return string
     *
     * @since 0.6.0
     */
    public function compileConditions(QueryBuilder $query): string;

    /**
     * Compiles ORDER BY clauses into SQL.
     *
     * @param QueryBuilder $query
     * @return string
     *
     * @since 0.6.0
     */
    public function compileOrders(QueryBuilder $query): string;

    /**
     * Compiles LIMIT clauses into SQL.
     *
     * @param QueryBuilder $query
     * @return string
     *
     * @since 0.7.0
     */
    public function compileLimit(QueryBuilder $query): string;
}
