<?php

namespace App\View\Components\Report\Ui;

use Illuminate\View\Component;

class InfoBox extends Component
{
    public function __construct(public string $title, public string $icon, public string $detailRoute)
    {
    }

    public function render()
    {
        return view('components.report.ui.info-box');
    }
}
