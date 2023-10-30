<?php

declare(strict_types=1);

namespace App\ClickHouseRepositories;

use App\DBCH;
use App\Entities\StatsReport;
use App\Entities\UrlStatistics;
use PDO;

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

    public function getStatsReport(): array
    {
        $sqlStatement = $this->db->prepare(sprintf('SELECT toMinute(created_at) as minute, count(*) as count, avg(length) as avg_length, min(created_at) as min_time, max(created_at) as max_time from `%1$s` GROUP BY toMinute(created_at)', $this->tblName));
        $sqlStatement->execute();

        $report = [];
        while($row = $sqlStatement->fetch(PDO::FETCH_ASSOC)){
            $report[] = new StatsReport(
                $row['minute'],
                $row['count'],
                (float)$row['avg_length'],
                new \DateTime($row['min_time']),
                new \DateTime($row['max_time']),
            );
        }

        return $report;
    }
}