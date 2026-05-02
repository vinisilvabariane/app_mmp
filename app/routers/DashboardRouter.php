<?php

namespace App\routers;

use App\controllers\DashboardController;

class DashboardRouter
{
    public function index(): void
    {
        (new DashboardController())->index();
    }

    public function questions(): void
    {
        (new DashboardController())->questions();
    }

    public function createQuestion(): void
    {
        (new DashboardController())->createQuestion();
    }

    public function updateQuestion(): void
    {
        (new DashboardController())->updateQuestion();
    }

    public function deleteQuestion(): void
    {
        (new DashboardController())->deleteQuestion();
    }

    public function metrics(): void
    {
        (new DashboardController())->metrics();
    }

    public function createMetric(): void
    {
        (new DashboardController())->createMetric();
    }

    public function updateMetric(): void
    {
        (new DashboardController())->updateMetric();
    }

    public function deleteMetric(): void
    {
        (new DashboardController())->deleteMetric();
    }
}
