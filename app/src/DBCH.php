<?php

declare(strict_types=1);

namespace App;

use PDO;

/**
 * @mixin PDO
 */
class DBCH
{
    private PDO $pdo;

    public function __construct(Config $config)
    {
        $dsn = sprintf('%s:host=%s;port=%d;dbname=%s', $config->dbCH['driver'], $config->dbCH['host'], $config->dbCH['port'], $config->dbCH['dbname']);
        $user = $config->dbCH['user'];
        $password = $config->dbCH['password'];
        $this->pdo = new PDO($dsn, $user, $password);
    }


    public function __call(string $method, array $arguments = [])
    {
        return call_user_func_array([$this->pdo, $method], $arguments);
    }
}