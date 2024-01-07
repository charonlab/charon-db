<?php

/*
 * This file is part of the abyss/database.
 *
 * Copyright (C) 2023-2024 Abyss Development Team
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */

namespace Abyss\Db\Adapter\Exception;

class NoActiveTransactionException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('There is no active transaction.');
    }
}
