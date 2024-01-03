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
 * @version 0.6.0
 */
enum ParameterType
{
    case NULL;
    case STRING;
    case INTEGER;
    case BINARY;
    case LARGE_OBJECT;
    case BOOLEAN;
}
