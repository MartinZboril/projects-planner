<?php

namespace App\Http\Controllers;

use App\Services\Release\IndexRelease;
use Illuminate\View\View;

class ReleaseController extends Controller
{
    public function __construct(
        private IndexRelease $indexRelease
    ) {
    }

    /**
     * Show the application releases.
     */
    public function __invoke(): View
    {
        return view('releases.index', ['releases' => $this->indexRelease->getReleases()]);
    }
}
