<?php

namespace App\View\Components\Release;

use Illuminate\View\Component;
use App\Services\Release\IndexRelease;

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
