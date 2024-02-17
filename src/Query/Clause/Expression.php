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

class Expression implements \Stringable
{
    public function __construct(
        public string $type,
        public string $expression
    ) {
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string {
        return $this->type . ' ' . $this->expression;
    }
}
