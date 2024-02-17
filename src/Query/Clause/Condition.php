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

class Condition implements \Stringable
{
    public function __construct(
        protected string $column,
        /** @var int|float|scalar[]|string $value */
        protected string|float|int|array $value,
        protected string $operator = '=',
        protected string $boolean = 'AND'
    ) {
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string {
        return \sprintf(
            '%s %s %s %s',
            $this->boolean,
            $this->column,
            $this->operator,
            \is_array($this->value) ? \implode(', ', $this->value) : $this->value
        );
    }
}
