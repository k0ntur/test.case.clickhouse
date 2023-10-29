<?php

declare(strict_types=1);

namespace App;

class Response
{
    public function __construct(
        protected string $content,
        protected int $code = 200
    )
    {
    }

    public function send(): void
    {
        http_response_code($this->code);

        echo $this->content;
    }

    public function __toString(): string
    {
        return $this->content;
    }
}