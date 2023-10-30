<?php

declare(strict_types=1);

namespace App\ClickHouseRepositories;

use App\DBCH;
use App\Entities\UrlStatistics;

class UrlsStatisticsRepository
{
    private string $tblName = 'urls_stat';
    public function __construct(
        private DBCH $db
    )
    {
    }

    public function insert(UrlStatistics $entity)
    {
        $sqlStatement = $this->db->prepare(sprintf('INSERT INTO `%1$s` (`url`, `length`, `created_at`) VALUES (?, ?, ?)', $this->tblName));

        $sqlStatement->execute([
            $entity->getUrl(),
            $entity->getLength(),
            $entity->getCreatedAt()->format('Y-m-d H:i:s')
        ]);
    }
}