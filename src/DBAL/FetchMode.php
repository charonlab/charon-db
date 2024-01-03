<?php

/*
 * This file is part of the abyss/database.
 *
 * Copyright (C) 2023-2024 Dominik Szamburski
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE.md file for details.
 */

namespace Abyss\DBAL;

/**
 * @author Dominik Szamburski
 * @package Abyss\DBAL
 * @license LGPL-2.1
 * @version 0.5.0
 */
enum FetchMode: int
{
    case ASSOC = \PDO::FETCH_ASSOC;
    case COLUMN = \PDO::FETCH_COLUMN;
    case NUMERIC = \PDO::FETCH_NUM;
    case OBJECT = \PDO::FETCH_OBJ;
}
