<?php

/*
 * This file is part of the abyss/database.
 *
 * Copyright (C) 2023-2024 Dominik Szamburski
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE.md file for details.
 */

namespace Abyss\DBAL\Query;

/**
 * @author Dominik Szamburski
 * @package Abyss\DBAL\Query
 * @license LGPL-2.1
 * @since 0.6.0
 */
enum QueryType
{
    case SELECT;
    case INSERT;
    case UPDATE;
    case DELETE;
}
