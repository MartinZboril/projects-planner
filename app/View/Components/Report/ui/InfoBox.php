<?php

namespace App\View\Components\Report\ui;

use Illuminate\View\Component;

class InfoBox extends Component
{
    public function __construct(public string $title, public string $detailRoute)
    {
    }

    public function render()
    {
        return view('components.report.ui.info-box');
    }
}
