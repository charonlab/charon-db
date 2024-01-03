<?php

/*
 * This file is part of the abyss/database.
 *
 * Copyright (C) 2023-2024 Dominik Szamburski
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE.md file for details.
 */

namespace Abyss\DBAL\Database;

use PDO;
use PDOStatement;

/**
 * Provides a Low Level abstraction at top of databases platform.
 *
 * @author Dominik Szamburski
 * @package Abyss\DBAL\Database
 * @license LGPL-2.1
 * @since 0.5.0
 *
 * @phpstan-import-type ConnectionParams from \Abyss\DBAL\ConnectionManager
 */
abstract class GenericDriver implements GenericDriverInterface
{
    protected int $transactionLevel = 0;

    protected ?PDO $pdo = null;

    /**
     * @param ConnectionParams $params
     */
    public function __construct(
        #[\SensitiveParameter] protected array $params
    ) {
    }

    /**
     * @inheritDoc
     */
    public function query(string $query): PDOStatement
    {
        $stmt = $this->getPDO()->query($query);
        assert($stmt instanceof PDOStatement);

        return $stmt;
    }

    /**
     * @inheritDoc
     */
    public function prepare(string $query): PDOStatement
    {
        $stmt = $this->getPDO()->prepare($query);
        assert($stmt instanceof PDOStatement);

        return $stmt;
    }

    /**
     * @inheritDoc
     */
    public function beginTransaction(string $isolationLevel = null): bool
    {
        ++$this->transactionLevel;

        if ($this->transactionLevel === 1) {
            try {
                return $this->getPDO()->beginTransaction();
            } catch (\Throwable $e) {
                $this->transactionLevel = 0;

                throw $e;
            }
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function commit(): bool
    {
        if (!$this->getPDO()->inTransaction()) {
            if ($this->transactionLevel === 0) {
                return false;
            }

            $this->transactionLevel = 0;
            return true;
        }

        --$this->transactionLevel;

        if ($this->transactionLevel === 0) {
            return $this->getPDO()->commit();
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function rollback(): bool
    {
        if (!$this->getPDO()->inTransaction()) {
            $this->transactionLevel = 0;
            return true;
        }

        --$this->transactionLevel;

        if ($this->transactionLevel === 0) {
            return $this->getPDO()->rollBack();
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function connect(): PDO
    {
        $options = $this->params['options'] ?? [];

        if (!empty($this->params['persistent'])) {
            $options[PDO::ATTR_PERSISTENT] = true;
        }

        return new PDO(
            dsn: $this->params['dsn'],
            username: $this->params['username'],
            password: $this->params['password'],
            options: $options ?? []
        );
    }

    /**
     * Returns an associated PDO connection, if
     * connection such does not exist tries to establish a new connection.
     *
     * @return PDO
     */
    protected function getPDO(): PDO
    {
        if ($this->pdo === null) {
            $this->pdo = $this->connect();
        }

        return $this->pdo;
    }
}
