<?php

/*
 * This file is part of the abyss/abyss-db.
 *
 * Copyright (C) 2023-2024 Abyss Development Team
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */

namespace Abyss\Db\Adapter;

use Nulldark\Stdlib\Collections\Collection;

/**
 * @template V
 * @extends Collection<V>
 * @implements ResultSetInterface<V>
 */
class ResultSet extends Collection implements ResultSetInterface
{
}
