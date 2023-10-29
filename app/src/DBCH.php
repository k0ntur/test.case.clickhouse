<?php

declare(strict_types=1);

namespace App;

use PDO;

class DBCH
{
    private PDO $pdo;

    public function __construct(Config $config)
    {
        $dsn = sprintf('%s:host=%s;port=%d;dbname=%s', $config->db['driver'], $config->db['host'], $config->db['port'], $config->db['dbname']);
        $user = $config->db['user'];
        $password = $config->db['password'];
        $this->pdo = new PDO($dsn, $user, $password);
    }
}