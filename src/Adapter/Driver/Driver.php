<?php

/*
 * This file is part of the charonlab/charon-db.
 *
 * Copyright (C) 2023-2024 Charon Lab Development Team
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */

namespace Charon\Db\Adapter\Driver;

/**
 * @psalm-import-type Params from \Charon\Db\Connection
 */
interface Driver
{
    /**
     * Attempts to establish connection to the database.
     *
     * @param Params $params
     *  An array of parameters to establish connection.
     *
     * @return Connection The database connection.
     *  The database connection.
     */
    public function connect(
        #[\SensitiveParameter] array $params
    ): Connection;
}
