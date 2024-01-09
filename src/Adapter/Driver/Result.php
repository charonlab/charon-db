<?php

/*
 * This file is part of the abyss/abyss-db.
 *
 * Copyright (C) 2023-2024 Abyss Development Team
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */

namespace Abyss\Db\Adapter\Driver;

interface Result
{
    /**
     * Returns the first value of the next row of the result.
     *
     * @return mixed|false
     *  Returns the first value, otherwise false if not found rows.
     */
    public function fetch(): mixed;

    /**
     * Returns an array containing all the result rows.
     *
     * @return array<array-key, mixed>
     *  Returns array of results.
     */
    public function fetchAll(): array;

    /**
     * Returns the number of rows affected by the DELETE, INSERT, or UPDATE statement that produced the result.
     *
     * @return int
     *  The number of rows.
     */
    public function rowCount(): int;

    /**
     * Returns the number of columns in the result.
     *
     * @return int
     *  The number of columns in the result.
     */
    public function columnCount(): int;
}
