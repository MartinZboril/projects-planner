<?php

namespace App\Http\Controllers;

use App\Services\Release\IndexRelease;
use Illuminate\View\View;

class ReleaseController extends Controller
{
    /**
     * Show the application releases.
     */
    public function __invoke(): View
    {
        return view('releases.index', ['releases' => (new IndexRelease)->getReleases()]);
    }
}
