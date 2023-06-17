<?php

namespace App\View\Components\Release;

use App\Services\Release\IndexRelease;
use Illuminate\View\Component;

class ActualRelease extends Component
{
    public $actualRelease;

    public $historyRoute;

    public function __construct()
    {
        $this->actualRelease = (new IndexRelease)->actualRelease;
        $this->historyRoute = route('releases');
    }

    public function render()
    {
        return view('components.release.actual-release');
    }
}
