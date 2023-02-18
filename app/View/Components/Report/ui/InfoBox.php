<?php

namespace App\View\Components\Report\ui;

use Illuminate\View\Component;

class InfoBox extends Component
{
    public $title;
    public $detailRoute;

    public function __construct($title, $detailRoute)
    {
        $this->title = $title;
        $this->detailRoute = $detailRoute;
    }

    public function render()
    {
        return view('components.report.ui.info-box');
    }
}
