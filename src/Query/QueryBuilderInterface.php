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
     * Sets a table to be used for INSERT statement.
     *
     * @param string $table
     *
     * @return self
     */
    public function insert(string $table): self;

    /**
     * Sets a table to be used for DELETE statement.
     *
     * @param string $table
     *
     * @return self
     */
    public function delete(string $table): self;

    /**
     * Sets a table to be used for UPDATE statement.
     *
     * @param string $table
     *
     * @return self
     */
    public function update(string $table): self;

    /**
     * Sets the query as DISTINCT or not.
     *
     * @param bool $distinct
     *
     * @return self
     */
    public function distinct(bool $distinct = true): self;

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
     * @param int|float|scalar[]|string $value
     * @param string $operator
     * @param string $boolean
     *
     * @return self
     */
    public function where(
        string $column,
        int|float|array|string $value,
        string $operator = '=',
        string $boolean = 'AND'
    ): self;

    /**
     * Adds a condition for restrictions to the query result.
     *
     * @param string $column
     * @param int|float|scalar[]|string $value
     * @param string $operator
     *
     * @return self
     */
    public function orWhere(string $column, int|float|array|string $value, string $operator = '='): self;

    /**
     * Adds a condition for restrictions to the query result.
     *
     * @param string $column
     * @param int|float|scalar[]|string $value
     * @param string $operator
     *
     * @return self
     */
    public function andWhere(string $column, int|float|array|string $value, string $operator = '='): self;

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
     * Adds a having expression to the query.
     *
     * @param string $expression
     *
     * @return self
     */
    public function having(string $expression): self;

    /**
     * Adds a OR having expression to the query.
     *
     * @param string $expression
     *
     * @return self
     */
    public function orHaving(string $expression): self;

    /**
     * Adds a AND having expression to the query.
     *
     * @param string $expression
     *
     * @return self
     */
    public function andHaving(string $expression): self;

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
     * Specifies values for an insert query indexed by column names.
     *
     * @param array<string, scalar> $values
     *
     * @return self
     */
    public function values(array $values): self;

    /**
     * Sets a new value for a update query.
     *
     * @param string $column
     * @param string $value
     *
     * @return self
     */
    public function set(string $column, string $value): self;

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
