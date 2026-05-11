<?php

namespace App\routers;

use App\controllers\TrailController;

class TrailRouter
{
    public function index(): void
    {
        (new TrailController())->index();
    }

    public function data(): void
    {
        (new TrailController())->data();
    }
}
