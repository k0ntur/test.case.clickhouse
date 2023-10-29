<?php

declare(strict_types=1);

namespace App;

class Response
{
    public function __construct(
        protected string $content
    )
    {
    }

    public function send(): void
    {
        echo $this->content;
    }

    public function __toString(): string
    {
        return $this->content;
    }
}