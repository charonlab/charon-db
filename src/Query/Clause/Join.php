<?php

/*
 * This file is part of the charonlab/charon-db.
 *
 * Copyright (C) 2023-2024 Charon Lab Development Team
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */

namespace Charon\Db\Query\Clause;

final readonly class Join implements \Stringable
{
    public function __construct(
        protected string $type,
        protected string $table,
        protected string $column,
        protected ?string $on = null,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string {
        return $this->type
            . ' JOIN '
            . $this->table
            . ' '
            . $this->column
            . ($this->on !==  null ? ' ON ' . $this->on  : '');
    }
}
