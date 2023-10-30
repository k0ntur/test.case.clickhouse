<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\UrlsStatisticsRepository;
use \App\ClickHouseRepositories\UrlsStatisticsRepository as CHUrlsStatisticsRepository;
use App\Response;

class HomeController
{
    public function __construct(
        protected UrlsStatisticsRepository $urlsStatisticsRepository,
        protected CHUrlsStatisticsRepository $churlsStatisticsRepository
    )
    {
    }

    public function index(): Response
    {
        $report = $this->urlsStatisticsRepository->getStatsReport();

        $html =
            '<h1>Maria DB</h1>'
            .'<table>'
            .'<tr><th>Минута</th><th>Количество</th><th>Средняя длинна</th><th>Время первого сообщения</th><th>Время последнего сообщения</th></th>';
        foreach ($report as $line){
            $html .= sprintf(
                '<tr><td>%02d</td><td>%d</td><td>%0.2f</td><td>%s</td><td>%s</td></tr>',
                $line->getMinute(),
                $line->getCount(),
                $line->getAvgLength(),
                $line->getMinTime()->format('Y-m-d H:i:s'),
                $line->getMaxTime()->format('Y-m-d H:i:s')
            );
        }
        $html .= '</table>';
        $html .= '<hr>';


        $report = $this->churlsStatisticsRepository->getStatsReport();
        $html .=
            '<h1>Click House</h1>'
            .'<table>'
            .'<tr><th>Минута</th><th>Количество</th><th>Средняя длинна</th><th>Время первого сообщения</th><th>Время последнего сообщения</th></th>';

        foreach ($report as $line){
            $html .= sprintf(
                '<tr><td>%02d</td><td>%d</td><td>%0.2f</td><td>%s</td><td>%s</td></tr>',
                $line->getMinute(),
                $line->getCount(),
                $line->getAvgLength(),
                $line->getMinTime()->format('Y-m-d H:i:s'),
                $line->getMaxTime()->format('Y-m-d H:i:s')
            );
        }

        $html .= '</table>';
        return new Response($html);
    }
}