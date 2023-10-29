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

        $this->config['db']['host'] = '';
        $this->config['db']['name'] = '';
        $this->config['db']['user'] = '';
        $this->config['db']['password'] = '';
        $this->config['db']['driver'] = '';

        $this->config['dbCH']['host'] = '';
        $this->config['dbCH']['name'] = '';
        $this->config['dbCH']['user'] = '';
        $this->config['dbCH']['password'] = '';
        $this->config['dbCH']['driver'] = '';
    }

    public function __get(string $name)
    {
        return $this->config[$name];
    }
}