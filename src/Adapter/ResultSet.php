<?php

/*
 * This file is part of the charonlab/charon-db.
 *
 * Copyright (C) 2023-2024 Charon Lab Development Team
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */

namespace Charon\Db\Adapter;

use Nuldark\Stdlib\StdArray;

/**
 * @template V
 * @extends StdArray<V>
 * @implements ResultSetInterface<V>
 */
class ResultSet extends StdArray implements ResultSetInterface
{
}
