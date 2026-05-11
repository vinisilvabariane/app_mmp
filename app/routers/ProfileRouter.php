<?php

namespace App\routers;

use App\controllers\ProfileController;

class ProfileRouter
{
    public function index(): void
    {
        (new ProfileController())->index();
    }

    public function trail(): void
    {
        (new ProfileController())->trail();
    }
}
