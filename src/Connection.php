<?php

/*
 * This file is part of the abyss/database.
 *
 * Copyright (C) 2023-2024 Abyss Development Team
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */

namespace Abyss\Db;

use Abyss\Db\Adapter\Driver\Connection as DriverConnection;
use Abyss\Db\Adapter\Driver\Driver;
use Abyss\Db\Adapter\Driver\Statement;
use Abyss\Db\Adapter\Exception\MissingOptionException;
use Abyss\Db\Adapter\Exception\NoActiveTransactionException;
use Abyss\Db\Adapter\Exception\QueryException;
use Abyss\Db\Adapter\Exception\UnsupportedDriverException;
use Abyss\Db\Adapter\Profiler\Profiler;
use Abyss\Db\Adapter\Profiler\ProfilerInterface;
use Abyss\Db\Adapter\ResultSet;
use Abyss\Db\Adapter\ResultSetInterface;
use Abyss\Db\Sql\ParameterType;

/**
 * Connection class is a High Level of abstraction at top of Driver.
 *
 * @psalm-type Params = array{
 *     driver: string,
 *     dsn: string,
 *     username?: string,
 *     password?: string,
 *     options?: array
 * }
 */
class Connection
{
    /**
     * @var int $transactionNestingLevel
     */
    private int $transactionNestingLevel = 0;

    /**
     * The parameters used to creates a new connection instance.
     *
     * @var Params $params
     */
    protected array $params;

    /**
     * The driver instance.
     *
     * @var \Abyss\Db\Adapter\Driver\Driver $driver
     */

    protected Driver $driver;

    /**
     * The driver connection.
     *
     * @var \Abyss\Db\Adapter\Driver\Connection|null $connection
     */
    private ?DriverConnection $connection = null;

    /**
     * The profiler instance.
     *
     * @var \Abyss\Db\Adapter\Profiler\ProfilerInterface $profiler
     */
    protected ProfilerInterface $profiler;

    /**
     * Initializes a new instance of the Connection class.
     *
     * @param Params $params The connection parameters.
     */
    public function __construct(
        #[\SensitiveParameter] array $params
    ) {
        $this->params = $params;

        $this->driver = $this->createDriver($params['driver']);
        $this->profiler = new Profiler();
    }

    /**
     * Gets a query profiler.
     *
     * @return \Abyss\Db\Adapter\Profiler\ProfilerInterface
     */
    public function getProfiler(): ProfilerInterface
    {
        return $this->profiler;
    }

    /**
     * Prepares an SQL statement.
     *
     * @param string $sql
     *  The SQL string to be preparing.
     *
     * @return \Abyss\Db\Adapter\Driver\Statement
     *  Returns a prepared statement.
     */
    public function prepare(string $sql): Statement
    {
        return $this->connect()->prepare($sql);
    }

    /**
     * Prepares and executes an SQL statement.
     *
     * @param string $sql
     *  The SQL string.
     * @param list<mixed>|array<string, mixed> $bindings
     *  An array of values with as many elements as there are bound parameters in the SQL statement being executed.
     *
     * @return \Abyss\Db\Adapter\ResultSetInterface
     */
    public function query(string $sql, array $bindings = []): ResultSetInterface
    {
        /**
         * @param string $sql
         * @param list<mixed>|array<string, mixed> $bindings
         * @return \Abyss\Db\Adapter\ResultSetInterface
         */
        $executor = function (string $sql, array $bindings): ResultSetInterface {
            if (\count($bindings) > 0) {
                $stmt = $this->prepare($sql);

                $this->bindValues($stmt, $bindings);

                $result = $stmt->execute()->fetchAll();
            } else {
                $result = $this->connect()->query($sql)->fetchAll();
            }

            return new ResultSet($result);
        };

        $results =  $this->run($sql, $bindings, $executor);

        if (!($results instanceof ResultSetInterface)) {
            $reason = \sprintf(
                "The return type must be instance of %s interface.",
                ResultSetInterface::class
            );

            throw new \RuntimeException($reason);
        }

        return $results;
    }

    public function execute(string $sql, array $bindings = []): int|string
    {
        /**
         * @param string $sql
         * @param list<mixed>|array<string, mixed> $bindings
         * @return int|string
         */
        $executor = function (string $sql, array $bindings): int|string {
            if (\count($bindings) > 0) {
                $stmt = $this->prepare($sql);

                $this->bindValues($stmt, $bindings);

                return $stmt->execute()->rowCount();
            }

            return $this->connect()->execute($sql);
        };

        return (int) $this->run($sql, $bindings, $executor);
    }

    /**
     * Starts SQL transaction with specified isolation level.
     *
     * @return bool
     *  Returns true if success, otherwise false.
     */
    public function beginTransaction(): bool
    {
        ++$this->transactionNestingLevel;

        return $this->connect()->beginTransaction();
    }

    /**
     * Commits the active transaction.
     *
     * @return bool
     *  Returns true if success, otherwise false.
     */
    public function commit(): bool
    {
        if ($this->transactionNestingLevel === 0) {
            throw new NoActiveTransactionException();
        }

        --$this->transactionNestingLevel;

        return $this->connect()->commit();
    }

    /**
     * Rollbacks the active transaction.
     *
     * @return bool
     *  Returns true if success, otherwise false.
     */
    public function rollback(): bool
    {
        if ($this->transactionNestingLevel === 0) {
            throw new NoActiveTransactionException();
        }

        --$this->transactionNestingLevel;

        return $this->connect()->rollback();
    }

    /**
     * Bind values to their parameters in the given statement.
     *
     * @param \Abyss\Db\Adapter\Driver\Statement $stmt
     * @param list<mixed>|array<string, mixed> $values
     *
     * @return void
     */
    public function bindValues(Statement $stmt, array $values): void
    {
        foreach ($values as $key => $value) {
            $stmt->bindValue(
                \is_int($key) ? $key + 1 : $key,
                $value,
                ParameterType::getType($value)
            );
        }
    }

    /**
     * Run a SQL statement, and profiling an execution.
     *
     * @param string $sql
     * @param list<mixed>|array<string, mixed> $bindings
     * @param \Closure $executor
     *
     * @return mixed
     */
    protected function run(string $sql, array $bindings, \Closure $executor): mixed
    {
        $this->connect();

        $this->profiler->start($sql, $bindings);

        try {
            return $executor($sql, $bindings);
        } catch (\Exception $exc) {
            throw new QueryException(
                $sql,
                $bindings,
                $exc
            );
        } finally {
            $this->profiler->stop();
        }
    }

    protected function connect(): DriverConnection
    {
        if ($this->connection !== null) {
            return $this->connection;
        }

        return $this->connection = $this->driver->connect($this->params);
    }

    /**
     * Returns a new driver instance for specify driver.
     *
     * @param ?string $driver
     *  A driver name.
     *
     * @return \Abyss\Db\Adapter\Driver\Driver
     *  Returns a new Driver instance.
     *
     * @throws \Abyss\Db\Adapter\Exception\MissingOptionException
     * @throws \Abyss\Db\Adapter\Exception\UnsupportedDriverException
     */
    private function createDriver(?string $driver): Driver
    {
        if ($driver === null) {
            throw new MissingOptionException("The option 'driver' is required.");
        }

        $driverClass = match ($driver) {
            'pdo' => Adapter\Driver\PDO\Driver::class,
            default => throw new UnsupportedDriverException("The given driver '$driver' is unknown.")
        };

        return new $driverClass();
    }
}