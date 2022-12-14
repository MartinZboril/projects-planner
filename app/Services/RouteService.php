<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;

class RouteService
{
    /**
     * Get redirect for the route
     */
    public function redirect(string $route, array $vars = []): RedirectResponse 
    {
        return redirect()->route($route, $vars);
    }
}