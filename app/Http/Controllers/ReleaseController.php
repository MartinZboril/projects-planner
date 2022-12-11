<?php

namespace App\Http\Controllers;

use App\Services\Release\IndexRelease;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReleaseController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application releases.
     */
    public function index(): View
    {
        return view('releases.index', ['releases' => (new IndexRelease)->getReleases()]);
    }
}
