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

use Abyss\DBAL\Database\GenericDriverFactory;
use Abyss\DBAL\Exception\ConnectionException;

/**
 * Automatic factory for Connections and drivers.
 *
 * @author Dominik Szamburski
 * @package Abyss\DBAL
 * @license LGPL-2.1
 * @version 0.5.0
 *
 * <code>
 *     $manager = new \Abyss\DBAL\ConnectionManager();
 *     $manager->addConnection([
 *          'driver' => 'mysql',
 *          'dsn' => 'mysql:host=127.0.0.1;dbname=foo'
 *          'username' => 'root',
 *          'password' => 'root'
 *      ], 'foo');
 *
 *      $results = $connection = $manager->connection('foo')
 *          ->query('SELECT 1+1)
 *          ->fetchAssociative()
 * </code>
 *
 * @phpstan-type ConnectionParams = array{
 *     'driver': string,
 *     'dsn': string,
 *     'username': string,
 *     'password': string,
 *    }
 */
final class ConnectionManager
{
    /**
     * The connection's collection.
     *
     * @var array<string, ConnectionInterface> $connections
     */
    private array $connections = [];

    /**
     * The database configuration collections.
     *
     * @var array<string, ConnectionParams> $configurations
     */
    private array $configurations = [];

    /**
     * The Driver factory.
     *
     * @var GenericDriverFactory $factory
     */
    private GenericDriverFactory $factory;

    public function __construct()
    {
        $this->factory = new GenericDriverFactory();
    }

    /**
     * Register new connections.
     *
     * @param ConnectionParams $config
     * @param string $name
     *
     * @return void
     */
    public function addConnection(array $config, string $name = 'default'): void
    {
        $this->configurations[$name] = $config;
    }

    /**
     * Gets a connection.
     *
     * @param string $name
     * @return ConnectionInterface
     *
     * @throws ConnectionException
     */
    public function connection(string $name = 'default'): ConnectionInterface
    {
        if (!isset($this->configurations[$name])) {
            throw new ConnectionException("Unable to create connection, no presets for `$name` found.");
        }

        if (!isset($this->connections[$name])) {
            $this->connections[$name] = $this->makeConnection($name);
        }

        return $this->connections[$name];
    }

    /**
     * Creates new Connection instance.
     *
     * @param string $name
     * @return \Abyss\DBAL\ConnectionInterface
     */
    private function makeConnection(string $name): ConnectionInterface
    {
        return new Connection(
            name: $name,
            driver: $this->factory->createDriver(
                $this->configurations[$name]
            )
        );
    }
}
