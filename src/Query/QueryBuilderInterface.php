<?php

/*
 * This file is part of the charonlab/charon-db.
 *
 * Copyright (C) 2023-2024 Charon Lab Development Team
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */

namespace Charon\Db\Query;

interface QueryBuilderInterface
{
    /**
     * Adds a columns to the selected.
     *
     * @param string ...$columns
     *
     * @return \Charon\Db\Query\QueryBuilderInterface
     */
    public function select(string ...$columns): self;

    /**
     * Adds a table to the FROM clause.
     *
     * @param string $table
     * @param string|null $alias
     *
     * @return \Charon\Db\Query\QueryBuilderInterface
     */
    public function from(string $table, string $alias = null): self;

    /**
     * Adds a condition for restrictions to the query result.
     *
     * @param string $column
     * @param int|float|array|string $value
     * @param string $operator
     *
     * @return self
     */
    public function where(string $column, int|float|array|string $value, string $operator = '='): self;

    /**
     * Adds an inner join to the query.
     *
     * @param string $fromAlias
     * @param string $table
     * @param string $alias
     * @param string|null $on
     *
     * @return self
     */
    public function join(string $fromAlias, string $table, string $alias, ?string $on = null): self;

    /**
     * Adds a left join to the query.
     *
     * @param string $fromAlias
     * @param string $table
     * @param string $alias
     * @param string|null $on
     *
     * @return self
     */
    public function leftJoin(string $fromAlias, string $table, string $alias, ?string $on = null): self;

    /**
     * Adds a right join to the query.
     *
     * @param string $fromAlias
     * @param string $table
     * @param string $alias
     * @param string|null $on
     *
     * @return self
     */
    public function rightJoin(string $fromAlias, string $table, string $alias, ?string $on = null): self;


    /**
     * Adds a grouping expression over the result of the query.
     *
     * @param string $column
     *
     * @return self
     */
    public function groupBy(string $column): self;

    /**
     * Adds an ordering expression to the result of the query.
     *
     * @param string $column
     * @param string $direction
     *
     * @return self
     */
    public function orderBy(string $column, string $direction = 'ASC'): self;

    /**
     * Adds a column to be selected.
     *
     * @param string $column
     * @param string|null $alias
     *
     * @return string
     */
    public function addColumn(string $column, ?string $alias = null): string;
}
