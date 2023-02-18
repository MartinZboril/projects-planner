<?php

namespace App\View\Components\Rate;

use Illuminate\View\Component;

class Table extends Component
{
    public $rates;
    public $tableId;
    public $editFormRouteName;

    public function __construct($rates, $tableId, $editFormRouteName)
    {
        $this->rates = $rates;
        $this->tableId = $tableId;
        $this->editFormRouteName = $editFormRouteName;
    }

    public function render()
    {
        return view('components.rate.table');
    }
}
