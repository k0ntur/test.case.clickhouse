<?php

declare(strict_types=1);

namespace App;
/**
 * @property array $db
 * @property array $dbCH
 */
class Config
{
    protected array $config;

    public function __construct()
    {
        $this->config['db'] = [];
        $this->config['dbCH'] = [];

        $this->config['db']['host'] = $_ENV['DB_HOST'];
        $this->config['db']['port'] = $_ENV['DB_PORT'];
        $this->config['db']['dbname'] = $_ENV['DB_NAME'];
        $this->config['db']['user'] = $_ENV['DB_USER'];
        $this->config['db']['password'] = $_ENV['DB_PASSWORD'];
        $this->config['db']['driver'] = $_ENV['DB_DRIVER'] ?? 'mysql';

        $this->config['dbCH']['host'] = $_ENV['DB_CH_HOST'];
        $this->config['dbCH']['port'] = $_ENV['DB_CH_PORT'];
        $this->config['dbCH']['dbname'] = $_ENV['DB_CH_NAME'];
        $this->config['dbCH']['user'] = $_ENV['DB_CH_USER'];
        $this->config['dbCH']['password'] = $_ENV['DB_CH_PASSWORD'];
        $this->config['dbCH']['driver'] = $_ENV['DB_CH_DRIVER']??'mysql';
    }

    public function __get(string $name)
    {
        return $this->config[$name];
    }
}