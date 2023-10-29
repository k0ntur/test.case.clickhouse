<?php

declare(strict_types=1);

namespace App\Controllers;

use App\DB;
use App\DBCH;
use App\Response;

class HomeController
{
    public function __construct(
        protected DB $mariadb,
        protected DBCH $chdb
    )
    {
    }

    public function index(): Response
    {
        return new Response('Hello index page!');
    }
}