<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;

interface DashboardInterface
{
    public function getDashboard(): Collection;
}
