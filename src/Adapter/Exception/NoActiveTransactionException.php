<?php

/*
 * This file is part of the charonlab/charon-db.
 *
 * Copyright (C) 2023-2024 Charon Lab Development Team
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */

namespace Charon\Db\Adapter\Exception;

use Charon\Db\Exception\RuntimeException;

class NoActiveTransactionException extends RuntimeException
{
    public function __construct() {
        parent::__construct('There is no active transaction.');
    }
}
