<?php

namespace App\Entities;

class StatsReport
{
    public function __construct(
        protected int $minute,
        protected int $count,
        protected float $avgLength,
        protected \DateTime $minTime,
        protected \DateTime $maxTime
    )
    {
    }

    public function getMinute(): int
    {
        return $this->minute;
    }

    public function getAvgLength(): float
    {
        return $this->avgLength;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getMinTime(): \DateTime
    {
        return $this->minTime;
    }

    public function getMaxTime(): \DateTime
    {
        return $this->maxTime;
    }
}