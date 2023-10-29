<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Response;

class HomeController
{

    public function index(): Response
    {
        return new Response('Hello index page!');
    }
}